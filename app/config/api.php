<?php

declare(strict_types=1);

return [
    'steganography' => [
        'base_url' => env('STEGANOGRAPHY_API_BASE_URL', 'http://localhost:8080'),
        'encodings' => ['bit', 'alpha'],
    ],
];
