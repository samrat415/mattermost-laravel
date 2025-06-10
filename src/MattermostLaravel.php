<?php

namespace Samrat415\MattermostLaravel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;
class MattermostLaravel {

    public function alert(Throwable $e, Request $request): void
    {
        if (!config('mattermost-laravel.enabled') || empty(config('mattermost-laravel.webhook_url')) ) {
            return;
        }
        $user = $request->user();
        $userType = $user ? class_basename($user) : 'Guest';
        $userInfo = $user ? [
            'ID' => $user->id,
            'Name' => $user->name ?? '',
            'Email' => $user->email ?? '',
        ] : ['Guest'];

        $data = [
            'text' => ':rotating_light: **Laravel Exception Alert**',
            'attachments' => [[
                'color' => '#D9534F',
                'fields' => [
                    [
                        'title' => 'App / Env',
                        'value' => config('app.name') . ' (`' . config('app.env') . '`)',
                        'short' => true,
                    ],
                    [
                        'title' => 'Host',
                        'value' => $request->getHost(),
                        'short' => true,
                    ],
                    [
                        'title' => 'User Type',
                        'value' => $userType,
                        'short' => true,
                    ],
                    [
                        'title' => 'User Info',
                        'value' => '```json' . "\n" . json_encode($userInfo, JSON_PRETTY_PRINT) . "\n```",
                        'short' => false,
                    ],
                    [
                        'title' => 'URL',
                        'value' => $request->fullUrl(),
                        'short' => false,
                    ],
                    [
                        'title' => 'Request Method',
                        'value' => $request->method(),
                        'short' => false,
                    ],
                    [
                        'title' => 'Payload',
                        'value' => '```json' . "\n" . json_encode($request->all(), JSON_PRETTY_PRINT) . "\n```",
                        'short' => false,
                    ],
                    [
                        'title' => 'Exception',
                        'value' => '`' . $e->getMessage() . '`',
                        'short' => false,
                    ],
                    [
                        'title' => 'File',
                        'value' => '`' . $e->getFile() . '`',
                        'short' => true,
                    ],
                    [
                        'title' => 'Line',
                        'value' => '`' . $e->getLine() . '`',
                        'short' => true,
                    ],
                ]
            ]]
        ];
        try {
             Http::post(config('mattermost-laravel.webhook_url'), $data);
        }catch (\Exception $ex) {
            throw $ex;
        }
    }
}
