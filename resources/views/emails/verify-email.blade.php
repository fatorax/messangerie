<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre adresse email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px;">
        <h1 style="color: #2c3e50; margin-bottom: 20px;">Vérification de votre adresse email</h1>

        <p>Bonjour {{ $user->username }},</p>

        <p>Merci de vous être inscrit sur {{ config('app.name') }}. Pour activer votre compte, veuillez vérifier votre adresse email en cliquant sur le bouton ci-dessous :</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('verify-email', ['email' => urlencode($user->email), 'hash' => $user->verify_token]) }}"
               style="background-color: #3498db; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                Vérifier mon adresse email
            </a>
        </div>

        <p style="font-size: 14px; color: #666;">
            Si le bouton ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :<br>
            <span style="word-break: break-all; color: #3498db;">{{ route('verify-email', ['email' => urlencode($user->email), 'hash' => $user->verify_token]) }}</span>
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

        <p style="font-size: 12px; color: #999;">
            Si vous n'avez pas créé de compte sur {{ config('app.name') }}, vous pouvez ignorer cet email.
        </p>

        <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
    </div>
</body>
</html>