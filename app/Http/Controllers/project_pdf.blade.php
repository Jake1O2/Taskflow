<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Projet: {{ $project->title }}</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        h1 { color: #2d3748; margin-bottom: 5px; }
        .meta { color: #718096; font-size: 0.9em; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; font-size: 12px; }
        th { background-color: #f7fafc; font-weight: bold; color: #4a5568; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .status-todo { background-color: #edf2f7; color: #4a5568; }
        .status-in_progress { background-color: #ebf8ff; color: #3182ce; }
        .status-done { background-color: #f0fff4; color: #38a169; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $project->title }}</h1>
        <div class="meta">
            <p>{{ $project->description }}</p>
            <p>
                <strong>Statut:</strong> {{ $project->status }} | 
                <strong>Créé le:</strong> {{ $project->created_at->format('d/m/Y') }}
            </p>
        </div>
    </div>

    <h3>Liste des tâches ({{ $project->tasks->count() }})</h3>
    
    <table>
        <thead>
            <tr>
                <th style="width: 40%">Titre</th>
                <th style="width: 15%">Priorité</th>
                <th style="width: 20%">Statut</th>
                <th style="width: 25%">Échéance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ ucfirst($task->priority ?? 'Normale') }}</td>
                <td><span class="badge status-{{ $task->status }}">{{ $task->status }}</span></td>
                <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>