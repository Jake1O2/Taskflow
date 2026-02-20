<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\SlackWorkspace;
use App\Models\Task;
use App\Services\SlackNotifier;
use App\Services\WebhookDispatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SlackController extends Controller
{
    public function commands(Request $request): JsonResponse
    {
        if (! $this->isValidSlackSignature($request)) {
            return response()->json(
                $this->commandResponse('Invalid Slack signature.'),
                401
            );
        }

        $slackTeamId = (string) $request->input('team_id', '');
        if ($slackTeamId === '') {
            return response()->json(
                $this->commandResponse('Missing Slack team context.'),
                422
            );
        }

        $workspace = SlackWorkspace::where('slack_team_id', $slackTeamId)
            ->where('active', true)
            ->first();

        if (! $workspace) {
            return response()->json(
                $this->commandResponse('No active TaskFlow integration found for this Slack workspace.')
            );
        }

        [$action, $argument] = $this->parseCommandText((string) $request->input('text', ''));

        return match ($action) {
            'create' => response()->json($this->handleCreateCommand($workspace, $argument)),
            'list' => response()->json($this->handleListCommand($workspace)),
            'status' => response()->json($this->handleStatusCommand($workspace)),
            default => response()->json(
                $this->commandResponse('Usage: /taskflow create [title] | /taskflow list | /taskflow status')
            ),
        };
    }

    public function index(): View
    {
        $integrations = SlackWorkspace::with('team')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $teams = Auth::user()->teams()->orderBy('name')->get();
        $channelsByIntegration = [];

        foreach ($integrations as $integration) {
            $channelsByIntegration[$integration->id] = $this->fetchChannels($integration);
        }

        return view('slack.index', [
            'integrations' => $integrations,
            'teams' => $teams,
            'channelsByIntegration' => $channelsByIntegration,
            'availableEvents' => $this->availableEvents(),
        ]);
    }

    public function connect(string $teamId): RedirectResponse
    {
        $team = Auth::user()->teams()->findOrFail($teamId);

        $clientId = (string) config('services.slack.client_id');
        $redirectUri = (string) config('services.slack.redirect_uri');

        if ($clientId === '' || $redirectUri === '') {
            return redirect()->route('slack.index')->with('error', 'Configuration Slack incomplète.');
        }

        $state = Str::random(40);
        session([
            'slack_oauth_state' => $state,
            'slack_oauth_team_id' => $team->id,
        ]);

        $url = 'https://slack.com/oauth/v2/authorize?' . http_build_query([
            'client_id' => $clientId,
            'scope' => 'chat:write,chat:write.public,channels:read,groups:read,incoming-webhook',
            'redirect_uri' => $redirectUri,
            'state' => $state,
        ]);

        return redirect()->away($url);
    }

    public function callback(Request $request): RedirectResponse
    {
        $expectedState = session()->pull('slack_oauth_state');
        $teamId = session()->pull('slack_oauth_team_id');

        if (! $expectedState || ! $teamId || $request->query('state') !== $expectedState) {
            return redirect()->route('slack.index')->with('error', 'État OAuth Slack invalide.');
        }

        $team = Auth::user()->teams()->find($teamId);
        if (! $team) {
            return redirect()->route('slack.index')->with('error', 'Équipe introuvable pour l’intégration Slack.');
        }

        $code = (string) $request->query('code', '');
        if ($code === '') {
            return redirect()->route('slack.index')->with('error', 'Code OAuth Slack manquant.');
        }

        $response = Http::asForm()
            ->timeout(20)
            ->post('https://slack.com/api/oauth.v2.access', [
                'client_id' => config('services.slack.client_id'),
                'client_secret' => config('services.slack.client_secret'),
                'code' => $code,
                'redirect_uri' => config('services.slack.redirect_uri'),
            ]);

        $payload = $response->json();
        if (! $response->ok() || ! ($payload['ok'] ?? false)) {
            $error = (string) ($payload['error'] ?? 'oauth_error');
            return redirect()->route('slack.index')->with('error', 'Connexion Slack échouée: ' . $error);
        }

        $token = (string) ($payload['access_token'] ?? '');
        $slackTeamId = (string) ($payload['team']['id'] ?? '');

        if ($token === '' || $slackTeamId === '') {
            return redirect()->route('slack.index')->with('error', 'Réponse OAuth Slack incomplète.');
        }

        $workspace = SlackWorkspace::firstOrNew([
            'slack_team_id' => $slackTeamId,
        ]);

        $workspace->user_id = (int) Auth::id();
        $workspace->team_id = $team->id;
        $workspace->slack_token = $token;
        $workspace->slack_channel_id = $payload['incoming_webhook']['channel_id'] ?? $workspace->slack_channel_id;
        $workspace->channel_name = $payload['incoming_webhook']['channel'] ?? $workspace->channel_name;
        $workspace->active = true;
        $workspace->events = is_array($workspace->events) ? $workspace->events : ['project.created'];
        $workspace->save();

        return redirect()->route('slack.index')->with('success', 'Workspace Slack connecté.');
    }

    public function disconnect(string $id): RedirectResponse
    {
        $workspace = SlackWorkspace::where('user_id', Auth::id())->findOrFail($id);
        $workspace->delete();

        return redirect()->route('slack.index')->with('success', 'Intégration Slack supprimée.');
    }

    public function selectChannel(string $id, Request $request): RedirectResponse
    {
        $workspace = SlackWorkspace::where('user_id', Auth::id())->findOrFail($id);
        $validated = $request->validate([
            'channel_id' => 'required|string|max:255',
        ]);

        $channels = $this->fetchChannels($workspace);
        $selected = collect($channels)->firstWhere('id', $validated['channel_id']);

        $workspace->update([
            'slack_channel_id' => $validated['channel_id'],
            'channel_name' => $selected['name'] ?? $workspace->channel_name,
            'active' => true,
        ]);

        return redirect()->route('slack.index')->with('success', 'Channel Slack mis à jour.');
    }

    public function updateEvents(string $id, Request $request): RedirectResponse
    {
        $workspace = SlackWorkspace::where('user_id', Auth::id())->findOrFail($id);
        $validated = $request->validate([
            'events' => 'nullable|array',
            'events.*' => ['string', Rule::in($this->availableEvents())],
        ]);

        $workspace->update([
            'events' => array_values($validated['events'] ?? []),
        ]);

        return redirect()->route('slack.index')->with('success', 'Événements Slack mis à jour.');
    }

    private function fetchChannels(SlackWorkspace $workspace): array
    {
        if (! $workspace->slack_token) {
            return [];
        }

        $response = Http::withToken($workspace->slack_token)
            ->timeout(15)
            ->get('https://slack.com/api/conversations.list', [
                'types' => 'public_channel,private_channel',
                'exclude_archived' => 'true',
                'limit' => 200,
            ]);

        $payload = $response->json();
        if (! $response->ok() || ! ($payload['ok'] ?? false)) {
            return [];
        }

        $channels = collect($payload['channels'] ?? [])
            ->filter(fn ($channel) => isset($channel['id'], $channel['name']))
            ->map(fn ($channel) => [
                'id' => (string) $channel['id'],
                'name' => (string) $channel['name'],
            ])
            ->sortBy('name')
            ->values();

        return $channels->all();
    }

    private function availableEvents(): array
    {
        return [
            'project.created',
            'task.created',
            'task.updated',
            'task.assigned',
            'comment.added',
            'team.member.added',
            'payment.received',
        ];
    }

    private function parseCommandText(string $text): array
    {
        $parts = preg_split('/\s+/', trim($text), 2) ?: [];
        $action = strtolower((string) ($parts[0] ?? ''));
        $argument = trim((string) ($parts[1] ?? ''));

        return [$action, $argument];
    }

    private function commandResponse(string $text, string $responseType = 'ephemeral'): array
    {
        return [
            'response_type' => $responseType,
            'text' => $text,
        ];
    }

    private function isValidSlackSignature(Request $request): bool
    {
        $signingSecret = (string) config('services.slack.signing_secret');
        if ($signingSecret === '') {
            return false;
        }

        $timestamp = (string) $request->header('X-Slack-Request-Timestamp', '');
        $signature = (string) $request->header('X-Slack-Signature', '');

        if ($timestamp === '' || $signature === '' || ! ctype_digit($timestamp)) {
            return false;
        }

        if (abs(time() - (int) $timestamp) > 300) {
            return false;
        }

        $base = 'v0:' . $timestamp . ':' . $request->getContent();
        $expected = 'v0=' . hash_hmac('sha256', $base, $signingSecret);

        return hash_equals($expected, $signature);
    }

    private function resolveDefaultProject(SlackWorkspace $workspace): ?Project
    {
        return Project::query()
            ->where('team_id', $workspace->team_id)
            ->orderByDesc('updated_at')
            ->first();
    }

    private function handleCreateCommand(SlackWorkspace $workspace, string $title): array
    {
        if ($title === '') {
            return $this->commandResponse('Usage: /taskflow create [title]');
        }

        $project = $this->resolveDefaultProject($workspace);
        if (! $project) {
            return $this->commandResponse('No project found for this team. Create a project first.');
        }

        $task = $project->tasks()->create([
            'title' => $title,
            'description' => null,
            'status' => 'todo',
        ]);

        $task->load(['project.team', 'assignee']);
        app(SlackNotifier::class)->notifyTaskCreated($task);
        app(WebhookDispatcher::class)->dispatch('task.created', $task->toArray(), (int) $workspace->user_id, false);

        return $this->commandResponse(
            "Task created in {$project->title}: {$task->title}",
            'in_channel'
        );
    }

    private function handleListCommand(SlackWorkspace $workspace): array
    {
        $project = $this->resolveDefaultProject($workspace);
        if (! $project) {
            return $this->commandResponse('No project found for this team.');
        }

        $tasks = $project->tasks()
            ->latest('created_at')
            ->limit(10)
            ->get();

        if ($tasks->isEmpty()) {
            return $this->commandResponse("No tasks yet in {$project->title}.");
        }

        $lines = $tasks->map(function (Task $task): string {
            $status = strtoupper(str_replace('-', '_', (string) $task->status));
            $due = $task->due_date ? ' - due ' . $task->due_date->format('Y-m-d') : '';

            return "- {$task->title} [{$status}]{$due}";
        })->implode("\n");

        return $this->commandResponse(
            "Latest tasks in {$project->title}:\n{$lines}",
            'in_channel'
        );
    }

    private function handleStatusCommand(SlackWorkspace $workspace): array
    {
        $project = $this->resolveDefaultProject($workspace);
        if (! $project) {
            return $this->commandResponse('No project found for this team.');
        }

        $counts = $project->tasks()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $todo = (int) ($counts['todo'] ?? 0);
        $inProgress = (int) ($counts['in_progress'] ?? 0);
        $done = (int) ($counts['done'] ?? 0);
        $total = $todo + $inProgress + $done;

        return $this->commandResponse(
            "Project status for {$project->title}: Total {$total} | TODO {$todo} | IN_PROGRESS {$inProgress} | DONE {$done}",
            'in_channel'
        );
    }
}
