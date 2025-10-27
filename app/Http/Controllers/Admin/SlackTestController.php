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

        $token = config('services.slack.notifications.bot_user_oauth_token');
        $channel = config('services.slack.notifications.channel');

        if (! $token || ! $channel) {
            return response()->json(['error' => 'Slack config missing'], 500);
        }

        $response = Http::withToken($token)->post('https://slack.com/api/chat.postMessage', [
            'channel' => $channel,
            'text' => "🧠 *New Emotional Check-in*\nMood: {$mood}\nNote: {$note}",
        ]);

        if (! $response->json('ok')) {
            Log::error('Slack notification failed', [
                'error' => $response->json('error'),
                'response' => $response->body(),
            ]);
            return response()->json($response->json(), 500);
        }

        return response()->json(['success' => true, 'message' => 'Notification sent to Slack']);
    }
}
