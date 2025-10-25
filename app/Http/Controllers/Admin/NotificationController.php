<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\EmotionalCheckin;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmotionalCheckinNotification;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Kirim email ke contact_id setelah check-in
     */
    public function sendToSelected(EmotionalCheckin $checkin)
    {
        // 1️⃣ Ambil contact_id
        $contactId = $checkin->contact_id;

        // Jika 'no_need' atau null, tidak perlu kirim
        if (!$contactId || $contactId === 'no_need') {
            return response()->json([
                'status' => 'ok',
                'sent_count' => 0
            ]);
        }

        // 2️⃣ Ambil user contact (integer id)
        $user = User::find($contactId);

        if (!$user) {
            return response()->json([
                'status' => 'ok',
                'sent_count' => 0
            ]);
        }

        // 3️⃣ Siapkan payload email
        $payload = [
            'name' => $user->name,
            'mood' => $checkin->mood ?? '-',
            'note' => $checkin->note ?? '-',
            'checkin_id' => $checkin->id,
            'subject' => 'Notifikasi: Emotional Check-in dari tim kamu'
        ];

        // 4️⃣ Kirim email
        Mail::to($user->email)->send(new EmotionalCheckinNotification($payload));

        // 5️⃣ Return response
        return response()->json([
            'status' => 'ok',
            'sent_count' => 1
        ]);
    }
}
