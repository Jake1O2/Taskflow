<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MentionParser
{
    private const MENTION_PATTERN = '/@(\w+)/';

    /**
     * Parse @mentions and resolve users.
     *
     * @return array{usernames: array<int, string>, user_ids: array<int, int>, users: \Illuminate\Support\Collection<int, \App\Models\User>}
     */
    public static function parseMentions(string $text): array
    {
        preg_match_all(self::MENTION_PATTERN, $text, $matches);
        $usernames = collect($matches[1] ?? [])
            ->filter()
            ->map(fn (string $username) => Str::lower($username))
            ->unique()
            ->values();

        if ($usernames->isEmpty()) {
            return [
                'usernames' => [],
                'user_ids' => [],
                'users' => collect(),
            ];
        }

        $users = self::resolveUsers($usernames);

        return [
            'usernames' => $usernames->all(),
            'user_ids' => $users->pluck('id')->map(fn ($id) => (int) $id)->all(),
            'users' => $users,
        ];
    }

    /**
     * Format mention text to safe HTML links.
     */
    public static function formatText(string $text): string
    {
        $parsed = self::parseMentions($text);
        $usersByKey = $parsed['users']->keyBy(function (User $user) {
            return self::normalizeMentionKey($user->name);
        });

        $escaped = e($text);

        return preg_replace_callback(self::MENTION_PATTERN, function (array $match) use ($usersByKey): string {
            $username = $match[1] ?? '';
            $key = self::normalizeMentionKey($username);
            $user = $usersByKey->get($key);

            if (! $user) {
                return '@' . e($username);
            }

            return '<a href="/users/' . $user->id . '" class="mention-link">@' . e($username) . '</a>';
        }, $escaped) ?? $escaped;
    }

    /**
     * Resolve usernames to users by exact name or email local part.
     *
     * @param \Illuminate\Support\Collection<int, string> $usernames
     * @return \Illuminate\Support\Collection<int, \App\Models\User>
     */
    private static function resolveUsers(Collection $usernames): Collection
    {
        $users = User::query()
            ->where(function ($query) use ($usernames) {
                foreach ($usernames as $username) {
                    $query->orWhere('name', 'like', '%' . $username . '%')
                        ->orWhere('email', 'like', $username . '@%');
                }
            })
            ->get(['id', 'name', 'email'])
            ->unique('id');

        return $users
            ->filter(function (User $user) use ($usernames): bool {
                $nameKey = self::normalizeMentionKey($user->name);
                $emailLocalPart = Str::lower((string) Str::before($user->email, '@'));

                return $usernames->contains($nameKey) || $usernames->contains($emailLocalPart);
            })
            ->values();
    }

    private static function normalizeMentionKey(string $value): string
    {
        return Str::lower((string) preg_replace('/\s+/', '', trim($value)));
    }
}
