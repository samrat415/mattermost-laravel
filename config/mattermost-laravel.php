<?php

// config for Samrat415/MattermostLaravel
return [
    'webhook_url' => env('MATTERMOST_WEBHOOK_URL'),
    'enabled' => env('MATTERMOST_ALERT_ENABLED', true),
    'redirect_back' => env('MATTERMOST_REDIRECT_BACK', true),
];
