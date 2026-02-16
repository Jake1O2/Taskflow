<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Projet: {{ $project->title }}</title>
    <style>
        @page { margin: 20px; }
        body { font-family: sans-serif; color: #333; font-size: 12px; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; color: #3182ce; margin-bottom: 10px; }
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
        .footer { position: fixed; bottom: 0; left: 0; right: 0; height: 30px; border-top: 1px solid #eee; padding-top: 10px; text-align: center; font-size: 10px; color: #718096; }
        .page-number:before { content: counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">TaskFlow</div>
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
                <th style="width: 20%">Statut</th>
                <th style="width: 25%">Échéance</th>
                <th style="width: 15%">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td><span class="badge status-{{ $task->status }}">{{ $task->status }}</span></td>
                <td>{{ $task->due_date ? $task->due_date->format('d/m/Y') : '-' }}</td>
                <td>{{ Str::limit($task->description, 50) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y H:i') }} | Page <span class="page-number"></span>
    </div>
    
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("sans-serif");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>