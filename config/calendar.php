<?php

return [
    'timezone' => env('APP_CALENDAR_TZ', 'Africa/Dar_es_Salaam'),
    'google' => [
        // Option 1: Paste full Google embed URL
        'embed_url' => env('GOOGLE_CALENDAR_EMBED_URL', ''),
        // Option 2: Provide calendar ID to auto-build the embed URL
        'calendar_id' => env('GOOGLE_CALENDAR_ID', ''),
        // Default view mode: month, agenda, week
        'mode' => env('GOOGLE_CALENDAR_MODE', 'month'),
    ],
];
