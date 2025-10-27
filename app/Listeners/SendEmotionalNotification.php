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

        Log::info('SendEmotionalNotification - checkin raw', $checkin->toArray());

        $token = config('services.slack.notifications.bot_user_oauth_token');
        $channel = config('services.slack.notifications.channel');

        if (! $token || ! $channel) {
            Log::error('Slack configuration missing.');
            return;
        }

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

        // ambil nama user
        $userName = $checkin->user->name ?? 'Unknown User';
        $userId   = $checkin->user->id ?? 'N/A';

        $text = "🧠 *New Emotional Check-in*\n" .
            "User: {$userName} (ID: {$userId})\n" .
            "Mood: {$mood}\n" .
            "Note: {$note}";

        $response = Http::withToken($token)->post('https://slack.com/api/chat.postMessage', [
            'channel' => $channel,
            'text' => $text,
        ]);

        if (! $response->json('ok')) {
            Log::error('Slack notification failed', [
                'error' => $response->json('error'),
                'body' => $response->body(),
                'channel' => $channel,
                'mood' => $mood,
                'note' => $note,
                'user' => $userName,
            ]);
        } else {
            Log::info('Slack notification sent', [
                'channel' => $channel,
                'user' => $userName,
                'mood' => $mood,
            ]);
        }
    }
}
