<?php

return [
    'themes' => ['light', 'dark', 'auto'],
    'languages' => ['en', 'fr', 'es'],
    'timezones' => timezone_abbreviations_list(),
    
    'colors' => [
        'primary' => [
            'default' => '#2563eb',
            'light' => '#3b82f6',
            'dark' => '#1e40af',
        ],
        'accent' => [
            'default' => '#059669',
            'light' => '#10b981',
            'dark' => '#047857',
        ],
    ],
    
    'fonts' => [
        'sans' => 'Inter, sans-serif',
        'mono' => 'JetBrains Mono, monospace',
    ],
];