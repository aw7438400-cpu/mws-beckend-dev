<?php

namespace App\Events;

use App\Models\EmotionalCheckin;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmotionalCheckinCreated
{
    use Dispatchable, SerializesModels;

    public $checkin;

    public function __construct(EmotionalCheckin $checkin)
    {
        $this->checkin = $checkin;
    }
}
