<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class SlackTestController extends Controller
{
    public function sendNotification(Request $request)
    {
        $mood = $request->input('mood', 'Happy');
        $note = $request->input('note', 'No note');
        $checkinId = uniqid('checkin_');

        $token = config('services.slack.notifications.bot_user_oauth_token');
        $channel = config('services.slack.notifications.channel');

        if (! $token || ! $channel) {
            return response()->json(['error' => 'Slack config missing'], 500);
        }

        $text = "🧠 *New Emotional Check-in*\nMood: {$mood}\nNote: {$note}";

        // Payload dengan tombol Konfirmasi
        $payload = [
            'channel' => $channel,
            'text' => $text, // fallback text
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $text,
                    ],
                ],
                [
                    'type' => 'actions',
                    'elements' => [
                        [
                            'type' => 'button',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => 'Konfirmasi Check-in',
                                'emoji' => true,
                            ],
                            'style' => 'primary',
                            'action_id' => 'confirm_checkin',
                            'value' => $checkinId,
                        ],
                    ],
                ],
            ],
        ];

        // Kirim pesan ke Slack
        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post('https://slack.com/api/chat.postMessage', $payload);

        Log::info('Slack response', $response->json());

        if (! $response->json('ok')) {
            Log::error('Slack notification failed', [
                'error' => $response->json('error'),
                'response' => $response->body(),
            ]);
            return response()->json($response->json(), 500);
        }

        return response()->json(['success' => true, 'message' => 'Notification sent to Slack with button']);
    }
}
