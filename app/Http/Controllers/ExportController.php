<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportProjectPDF(string $projectId)
    {
        $project = Auth::user()->projects()->with('tasks')->findOrFail($projectId);
        
        // Suppose une vue 'exports.project_pdf' existe
        $pdf = Pdf::loadView('exports.project_pdf', compact('project'));
        
        return $pdf->download("project-{$project->id}.pdf");
    }

    public function exportTasksCSV(string $projectId): StreamedResponse
    {
        $project = Auth::user()->projects()->with('tasks')->findOrFail($projectId);
        $tasks = $project->tasks;

        return response()->streamDownload(function () use ($tasks) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Titre', 'Description', 'Statut', 'Priorité', 'Date d\'échéance']);

            foreach ($tasks as $task) {
                fputcsv($handle, [
                    $task->id,
                    $task->title,
                    $task->description,
                    $task->status,
                    $task->priority ?? 'normal',
                    $task->due_date?->format('Y-m-d') ?? 'N/A',
                ]);
            }
            fclose($handle);
        }, "tasks-project-{$project->id}.csv");
    }
}