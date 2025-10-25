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

        $token = config('services.slack.notifications.bot_user_oauth_token');
        $channel = config('services.slack.notifications.channel');

        if (! $token || ! $channel) {
            Log::error('Slack configuration missing.');
            return;
        }

        Http::withToken($token)->post('https://slack.com/api/chat.postMessage', [
            'channel' => $channel,
            'text' => "🧠 *New Emotional Check-in*\nMood: {$checkin->mood}\nNote: {$checkin->note}",
        ]);
    }
}
