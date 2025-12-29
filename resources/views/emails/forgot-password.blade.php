<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px;">
        <h1 style="color: #2c3e50; margin-bottom: 20px;">Réinitialisation de votre mot de passe</h1>

        <p>Bonjour {{ $user->username }},</p>

        <p>Vous avez demandé une réinitialisation de votre mot de passe sur {{ config('app.name') }}. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('reset-password', ['email' => urlencode($user->email), 'token' => $token]) }}"
               style="background-color: #e74c3c; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
                Réinitialiser mon mot de passe
            </a>
        </div>

        <p style="font-size: 14px; color: #666;">
            Si le bouton ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :<br>
            <span style="word-break: break-all; color: #e74c3c;">{{ route('reset-password', ['email' => urlencode($user->email), 'token' => $token]) }}</span>
        </p>

        <p style="font-size: 14px; color: #666; background-color: #fff3cd; padding: 10px; border-radius: 5px; margin-top: 20px;">
            ⚠️ Ce lien expirera dans un délai limité. Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

        <p style="font-size: 12px; color: #999;">
            Pour des raisons de sécurité, ne partagez jamais ce lien avec quelqu'un d'autre.
        </p>

        <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
    </div>
</body>
</html>