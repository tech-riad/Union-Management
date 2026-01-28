<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SVG Image Backend
    |--------------------------------------------------------------------------
    |
    | This option specifies the backend to use for generating SVG images.
    | We recommend using the "svg" backend as it doesn't require imagick.
    |
    | Supported: "svg", "imagick", "eps"
    |
    */
    'svg' => true,

    /*
    |--------------------------------------------------------------------------
    | Image Backend
    |--------------------------------------------------------------------------
    |
    | This option specifies the backend to use for generating images.
    | You can use "imagick" if installed, or fallback to "svg".
    |
    | Supported: "imagick", "svg", "eps"
    |
    */
    'image' => 'svg', // Change from 'imagick' to 'svg'

    /*
    |--------------------------------------------------------------------------
    | EPS Backend
    |--------------------------------------------------------------------------
    |
    | This option specifies the backend to use for generating EPS images.
    |
    | Supported: "imagick", "svg", "eps"
    |
    */
    'eps' => 'svg',

    /*
    |--------------------------------------------------------------------------
    | Size
    |--------------------------------------------------------------------------
    |
    | This option defines the size of the QR code in pixels.
    |
    */
    'size' => 100,

    /*
    |--------------------------------------------------------------------------
    | Margin
    |--------------------------------------------------------------------------
    |
    | This option defines the margin around the QR code.
    |
    */
    'margin' => 0,

    /*
    |--------------------------------------------------------------------------
    | Error Correction
    |--------------------------------------------------------------------------
    |
    | This option defines the error correction level for the QR code.
    |
    | Supported: "L", "M", "Q", "H"
    |
    */
    'error_correction' => 'H',

    /*
    |--------------------------------------------------------------------------
    | Encoding
    |--------------------------------------------------------------------------
    |
    | This option defines the encoding for the QR code.
    |
    */
    'encoding' => 'UTF-8',

    /*
    |--------------------------------------------------------------------------
    | Colors
    |--------------------------------------------------------------------------
    |
    | These options define the foreground and background colors for the QR code.
    |
    */
    'colors' => [
        'foreground' => '#000000',
        'background' => '#ffffff',
    ],

    /*
    |--------------------------------------------------------------------------
    | Style
    |--------------------------------------------------------------------------
    |
    | This option defines the style for the QR code.
    |
    | Supported: "square", "dot", "round"
    |
    */
    'style' => 'square',
];