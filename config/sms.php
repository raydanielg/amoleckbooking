<?php

return [
    'provider' => env('SMS_PROVIDER', 'valuesms'), // valuesms | oasis

    // ValueSMS (https://valuesms.ae/sendsms_api_json.aspx)
    'valuesms' => [
        'user' => env('VALUESMS_USER', ''),
        'pwd' => env('VALUESMS_PWD', ''),
        'sender' => env('VALUESMS_SENDER', 'SMS Alert'),
        'language' => env('VALUESMS_LANGUAGE', 'English'), // English | Unicode
        'url' => env('VALUESMS_URL', 'https://valuesms.ae/sendsms_api_json.aspx'),
    ],

    // OasisTech (http://sms.oasistech.co.tz)
    'oasis' => [
        'user' => env('OASIS_USER', ''),
        'pwd' => env('OASIS_PWD', ''),
        'sender' => env('OASIS_SENDER', 'SMS Alert'),
        'priority' => env('OASIS_PRIORITY', 'High'),
        'country_code' => env('OASIS_COUNTRY_CODE', 'ALL'),
        'url' => env('OASIS_URL', 'http://sms.oasistech.co.tz/sendurlcomma.aspx'),
    ],

    // Default country code to prepend if a local number starts with 0 (e.g. 255 for TZ)
    'default_country_code' => env('DEFAULT_COUNTRY_CODE', ''),
];
