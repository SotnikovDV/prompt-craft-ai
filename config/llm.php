<?php

return [
    'perplexity' => [
        'api_key' => env('PERPLEXITY_API_KEY'),
        'base_url' => env('PERPLEXITY_BASE_URL', 'https://api.perplexity.ai'),
        'models' => [
            'sonar' => 'sonar',
            'sonarpro' => 'sonar-pro',
        ],
    ],
];
