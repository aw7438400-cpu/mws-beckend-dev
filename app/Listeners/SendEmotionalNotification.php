<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Events\EmotionalCheckinCreated;

class SendEmotionalNotification
{
    public function handle(EmotionalCheckinCreated $event)
    {
        $checkin = $event->checkin;

        // Config
        $token = config('services.slack.notifications.bot_user_oauth_token'); // xoxb-...
        $userMap = config('services.slack.notifications.user_map', []);

        $contactId = $checkin->contact['id'] ?? null;
        $mapped = $userMap[$contactId] ?? null;

        if (!$token) {
            Log::error('Slack bot token missing in config/services.php');
            return;
        }

        Log::info('Starting Slack DM process', [
            'contact_id' => $contactId,
            'mapped' => $mapped,
        ]);

        if (!$mapped) {
            Log::error('No slack mapping found for contact_id', ['contact_id' => $contactId]);
            return;
        }

        // quick normalizer
        $norm = function ($v) {
            if (is_null($v)) return 'No note';
            if (is_string($v)) {
                $trim = trim($v);
                if (($trim[0] ?? '') === '[' || ($trim[0] ?? '') === '{') {
                    $decoded = json_decode($v, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return is_array($decoded) ? implode(', ', $decoded) : (string) $decoded;
                    }
                }
                return $v === '' ? 'No note' : $v;
            }
            if (is_array($v)) return implode(', ', $v) ?: 'No note';
            if (is_object($v)) return method_exists($v, '__toString') ? (string) $v : json_encode($v);
            return (string) $v;
        };

        $mood = $norm($checkin->mood ?? null);
        $note = $norm($checkin->note ?? null);
        $userName = $checkin->user['name'] ?? 'Unknown User';
        $userId = $checkin->user['id'] ?? 'N/A';

        $text = "🧠 *New Emotional Check-in*\nUser: {$userName} (ID: {$userId})\nMood: {$mood}\nNote: {$note}";

        // 1) auth.test -> verifikasi token & workspace
        $auth = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get('https://slack.com/api/auth.test');

        Log::info('auth.test response', $auth->json());

        if (!$auth->ok() || !$auth->json('ok')) {
            Log::error('Slack auth.test failed', ['resp' => $auth->json()]);
            return;
        }

        // Resolve slack user id: mapping may be email or Uxxxx
        $slackUserId = $mapped;
        if (filter_var($mapped, FILTER_VALIDATE_EMAIL)) {
            Log::info('Mapping looks like email, calling users.lookupByEmail', ['email' => $mapped]);
            $lookup = Http::withToken($token)
                ->withHeaders(['Accept' => 'application/json'])
                ->get('https://slack.com/api/users.lookupByEmail', ['email' => $mapped]);

            Log::info('users.lookupByEmail response', $lookup->json());

            if (!$lookup->ok() || !$lookup->json('ok')) {
                Log::error('users.lookupByEmail failed', ['email' => $mapped, 'resp' => $lookup->json()]);
                return;
            }

            $slackUserId = $lookup->json('user.id') ?? null;
            if (!$slackUserId) {
                Log::error('Lookup did not return user id', ['resp' => $lookup->json()]);
                return;
            }
        }

        // 2) users.info -> pastikan user ada & aktif
        $userInfo = Http::withToken($token)
            ->withHeaders(['Accept' => 'application/json'])
            ->get('https://slack.com/api/users.info', ['user' => $slackUserId]);

        Log::info('users.info response', $userInfo->json());

        if (!$userInfo->ok() || !$userInfo->json('ok')) {
            Log::error('users.info failed', ['slack_user_id' => $slackUserId, 'resp' => $userInfo->json()]);
            return;
        }

        // Validate ID format
        if (!preg_match('/^U[A-Z0-9]+$/', $slackUserId)) {
            Log::error('Invalid slack user id format (expect Uxxxx)', ['slack_user_id' => $slackUserId]);
            return;
        }

        // 3) conversations.open -> buka DM
        $openPayload = json_encode(['users' => $slackUserId]);

        $dmResponse = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->withBody($openPayload, 'application/json')
            ->post('https://slack.com/api/conversations.open');

        Log::info('conversations.open response', $dmResponse->json());

        if (!$dmResponse->ok() || !$dmResponse->json('ok')) {
            Log::error('Failed to open DM channel', [
                'contact_id' => $contactId,
                'slack_user_id' => $slackUserId,
                'response' => $dmResponse->json()
            ]);
            return;
        }

        $dmChannel = $dmResponse->json('channel.id') ?? null;
        if (!$dmChannel) {
            Log::error('conversations.open returned no channel.id', ['resp' => $dmResponse->json()]);
            return;
        }

        // IMPORTANT: DM channel id should start with 'D'
        if (strpos($dmChannel, 'D') !== 0) {
            Log::warning('conversations.open returned channel not starting with D', [
                'channel' => $dmChannel,
                'resp' => $dmResponse->json()
            ]);
            return;
        }

        // 4) kirim pesan dengan tombol Konfirmasi
        $postPayload = [
            'channel' => $dmChannel,
            'text' => $text, // fallback text
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => $text
                    ]
                ],
                [
                    'type' => 'actions',
                    'elements' => [
                        [
                            'type' => 'button',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => 'Konfirmasi Check-in',
                                'emoji' => true
                            ],
                            'style' => 'primary',
                            'action_id' => 'confirm_checkin', // nanti dipakai di listener
                            'value' => $checkin->id ?? null
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->withBody(json_encode($postPayload), 'application/json')
            ->post('https://slack.com/api/chat.postMessage');

        Log::info('chat.postMessage response', $response->json());

        if (!$response->ok() || !$response->json('ok')) {
            Log::error('Slack notification failed', ['resp' => $response->json(), 'channel' => $dmChannel]);
            return;
        }

        Log::info('Slack DM sent with button', ['channel' => $dmChannel, 'slack_user_id' => $slackUserId]);
    }
}
