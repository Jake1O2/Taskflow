<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Project;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportProjectPDF(string $projectId)
    {
        $user = Auth::user();

        // Récupérer les IDs des équipes (propriétaire ou membre)
        $teamIds = $user->teams()->pluck('id')
            ->merge($user->teamMemberships()->pluck('teams.id'))
            ->unique();

        $project = Project::with('tasks')
            ->where('id', $projectId)
            ->where(function ($query) use ($user, $teamIds) {
                $query->where('created_by', $user->id)
                      ->orWhereIn('team_id', $teamIds);
            })
            ->firstOrFail();
        
        // Suppose une vue 'exports.project_pdf' existe
        $pdf = Pdf::loadView('exports.project_pdf', compact('project'))
            ->setPaper('a4', 'portrait')
            ->setWarnings(false);
        
        return $pdf->download("project-{$project->id}.pdf")->with('success', 'PDF généré et téléchargé');
    }

    public function exportTasksCSV(string $projectId): StreamedResponse
    {
        $project = Auth::user()->projects()->with('tasks')->findOrFail($projectId);
        $tasks = $project->tasks;

        return response()->streamDownload(function () use ($tasks) {
            $handle = fopen('php://output', 'w');
            
            // BOM pour UTF-8 Excel
            fputs($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, ['Titre', 'Status', 'Échéance', 'Description']);

            foreach ($tasks as $task) {
                fputcsv($handle, [
                    $task->title,
                    $task->status,
                    $task->due_date?->format('Y-m-d') ?? 'N/A',
                    $task->description,
                ]);
            }
            fclose($handle);
        }, "tasks-project-{$project->id}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}