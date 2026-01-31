<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FirebasePushService
{
    public static function sendNewOrderNotification($orderId)
    {
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('app/firebase-admin.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];

        $tokens = DB::table('admin_fcm_tokens')->pluck('token');

        foreach ($tokens as $token) {
            Http::withToken($accessToken)->post(
                'https://fcm.googleapis.com/v1/projects/mister-wang-7a3ba/messages:send',
                [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => '🍜 Nova porudžbina',
                            'body' => "Stigla je nova porudžbina #{$orderId}",
                        ],
                    ],
                ]
            );
        }
    }
}
