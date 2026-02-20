<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation TaskFlow</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <p>Bonjour {{ $invitation->email }},</p>
    <p>Vous êtes invité à rejoindre l'équipe <strong>{{ $invitation->team->name }}</strong> sur TaskFlow.</p>
    <p style="margin-top: 24px;">
        <a href="{{ url('/invitations/'.$invitation->token.'/accept') }}" style="display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff !important; text-decoration: none; border-radius: 6px; margin-right: 8px;">Accepter</a>
        <a href="{{ url('/invitations/'.$invitation->token.'/decline') }}" style="display: inline-block; padding: 12px 24px; background: #6b7280; color: #fff !important; text-decoration: none; border-radius: 6px;">Refuser</a>
    </p>
    <p style="margin-top: 32px; font-size: 12px; color: #6b7280;">© TaskFlow 2026</p>
</body>
</html>
