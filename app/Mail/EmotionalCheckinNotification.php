<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmotionalCheckinNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        return $this->subject($this->payload['subject'] ?? 'Notifikasi Emotional Check-in')
            ->markdown('emails.emotional_checkin')
            ->with($this->payload);
    }
}
