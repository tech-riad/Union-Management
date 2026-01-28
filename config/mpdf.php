<?php

return [
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font' => 'solaimanlipi',
    'default_font_size' => 12,
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_header' => 9,
    'margin_footer' => 9,
    'orientation' => 'P',
    
    // Font configuration
    'font_path' => public_path('fonts/'),
    'font_data' => [
        'solaimanlipi' => [
            'R' => 'solaimanlipi.ttf',
            'B' => 'solaimanlipi.ttf',
            'I' => 'solaimanlipi.ttf',
            'BI' => 'solaimanlipi.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'nikosh' => [
            'R' => 'Nikosh.ttf',
            'B' => 'Nikosh.ttf',
            'I' => 'Nikosh.ttf',
            'BI' => 'Nikosh.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
        'siyamrupali' => [
            'R' => 'SiyamRupali.ttf',
            'B' => 'SiyamRupali.ttf',
            'I' => 'SiyamRupali.ttf',
            'BI' => 'SiyamRupali.ttf',
            'useOTL' => 0xFF,
            'useKashida' => 75,
        ],
    ],
];