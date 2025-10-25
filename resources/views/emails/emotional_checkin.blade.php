<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emotional Check-in</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Halo {{ $name }},</h2>
    <p>Ada check-in yang membutuhkan perhatianmu!</p>

    <p><strong>Mood:</strong>
        @if(is_array($mood))
            {{ implode(', ', $mood) }}
        @else
            {{ $mood }}
        @endif
    </p>

    <p><strong>Catatan:</strong> {{ $note }}</p>

    @if(isset($checkin_id))
    <a href="{{ url('/confirm-checkin/'.$checkin_id) }}" 
       style="background:#4CAF50;color:white;padding:10px 20px;text-decoration:none;border-radius:6px;">
       Konfirmasi Check-in
    </a>
    @endif

    <p style="margin-top:20px;">Terima kasih,<br>Tim Emotional Tracker</p>
</body>
</html>
