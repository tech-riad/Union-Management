<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TCPDF Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file provides the default settings for TCPDF.
    | You can override these settings in your .env file.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Page Settings
    |--------------------------------------------------------------------------
    */
    'page_format' => 'A4',
    'page_orientation' => 'P', // P = Portrait, L = Landscape
    'page_units' => 'mm',
    
    /*
    |--------------------------------------------------------------------------
    | Font & Encoding Settings (Important for Bangla)
    |--------------------------------------------------------------------------
    */
    'unicode' => true,
    'encoding' => 'UTF-8',
    'font' => 'freeserif', // Default font for Bangla support
    
    /*
    |--------------------------------------------------------------------------
    | Directory Paths
    |--------------------------------------------------------------------------
    */
    'font_directory' => storage_path('fonts/tcpdf'),
    'image_directory' => public_path('images'),
    
    /*
    |--------------------------------------------------------------------------
    | TCPDF Constants Configuration
    |--------------------------------------------------------------------------
    |
    | These constants are defined in TCPDF library.
    | Do not change unless you know what you're doing.
    |
    */
    'tcpdf' => [
        // Main path
        'K_PATH_MAIN' => base_path('vendor/tecnickcom/tcpdf/'),
        
        // Font path (will be created if doesn't exist)
        'K_PATH_FONTS' => storage_path('fonts/tcpdf/'),
        
        // Images path
        'K_PATH_IMAGES' => public_path(),
        
        // Cache directory for HTML fonts
        'K_PATH_CACHE' => storage_path('framework/cache/'),
        
        // Blank image
        'K_BLANK_IMAGE' => '_blank.png',
        
        // Cell height ratio
        'K_CELL_HEIGHT_RATIO' => 1.25,
        
        // Title magnification
        'K_TITLE_MAGNIFICATION' => 1.3,
        
        // Small ratio
        'K_SMALL_RATIO' => 2/3,
        
        // Thai topchars
        'K_THAI_TOPCHARS' => true,
        
        // TCPDF calls in HTML
        'K_TCPDF_CALLS_IN_HTML' => false,
        
        // Timezone
        'K_TIMEZONE' => config('app.timezone', 'UTC'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Default PDF Settings
    |--------------------------------------------------------------------------
    */
    'creator' => env('APP_NAME', 'Laravel TCPDF'),
    'author' => env('APP_NAME', 'Laravel Application'),
    'title' => 'TCPDF Document',
    'subject' => 'TCPDF Document',
    'keywords' => 'TCPDF, PDF, Laravel',
    
    /*
    |--------------------------------------------------------------------------
    | Header & Footer Settings
    |--------------------------------------------------------------------------
    */
    'header' => [
        'enabled' => false,
        'font' => 'helvetica',
        'font_size' => 10,
        'text' => 'TCPDF Header',
        'line' => true,
    ],
    
    'footer' => [
        'enabled' => false,
        'font' => 'helvetica',
        'font_size' => 8,
        'text' => 'Page {PAGENO} of {nb}',
        'line' => true,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Margins (in mm)
    |--------------------------------------------------------------------------
    */
    'margins' => [
        'top' => 27,
        'right' => 15,
        'bottom' => 25,
        'left' => 15,
        'header' => 5,
        'footer' => 10,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Image Scale
    |--------------------------------------------------------------------------
    */
    'image_scale' => 4,
    
    /*
    |--------------------------------------------------------------------------
    | Font Settings for Bangla
    |--------------------------------------------------------------------------
    */
    'fonts' => [
        'freeserif' => [
            'name' => 'FreeSerif',
            'type' => 'TrueTypeUnicode',
            'support_bangla' => true,
        ],
        'kozgopromedium' => [
            'name' => 'KozGoPro-Medium',
            'type' => 'TrueTypeUnicode',
            'support_bangla' => false,
        ],
        'helvetica' => [
            'name' => 'Helvetica',
            'type' => 'core',
            'support_bangla' => false,
        ],
        'times' => [
            'name' => 'Times-Roman',
            'type' => 'core',
            'support_bangla' => false,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Custom Fonts for Bangla
    |--------------------------------------------------------------------------
    | You can add custom Bangla fonts here
    */
    'custom_fonts' => [
        'nikosh' => [
            'name' => 'Nikosh',
            'type' => 'TrueTypeUnicode',
            'file' => storage_path('fonts/Nikosh.ttf'),
        ],
        'solaimanlipi' => [
            'name' => 'SolaimanLipi',
            'type' => 'TrueTypeUnicode',
            'file' => storage_path('fonts/SolaimanLipi.ttf'),
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Debug Settings
    |--------------------------------------------------------------------------
    */
    'debug' => env('TCPDF_DEBUG', false),
    'log_file' => storage_path('logs/tcpdf.log'),
    
    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'enabled' => false,
        'user_password' => '',
        'owner_password' => '',
        'permissions' => [
            'print' => true,
            'modify' => true,
            'copy' => true,
            'annot-forms' => true,
        ],
        'cipher' => 'aes256',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Color Settings
    |--------------------------------------------------------------------------
    */
    'colors' => [
        'text' => [0, 0, 0], // Black
        'draw' => [0, 0, 0],
        'fill' => [255, 255, 255], // White
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Compression
    |--------------------------------------------------------------------------
    */
    'compression' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Resolution (DPI)
    |--------------------------------------------------------------------------
    */
    'resolution' => 96,
];