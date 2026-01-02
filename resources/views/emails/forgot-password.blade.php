<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #e74c3c;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .info-box {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #e74c3c;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #e74c3c;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .link-fallback {
            font-size: 14px;
            color: #666;
            word-break: break-all;
        }
        .link-fallback a {
            color: #e74c3c;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔑 Réinitialisation du mot de passe</h1>
    </div>
    <div class="content">
        <p>Bonjour {{ $user->username }},</p>

        <p>Vous avez demandé une réinitialisation de votre mot de passe sur {{ config('app.name') }}. Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe :</p>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ route('reset-password', ['email' => urlencode($user->email), 'token' => $token]) }}" class="btn">
                Réinitialiser mon mot de passe
            </a>
        </p>

        <div class="info-box">
            <p class="link-fallback">
                Si le bouton ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :<br>
                <a href="{{ route('reset-password', ['email' => urlencode($user->email), 'token' => $token]) }}">{{ route('reset-password', ['email' => urlencode($user->email), 'token' => $token]) }}</a>
            </p>
        </div>

        <div class="warning">
            <strong>⚠️ Attention :</strong> Ce lien expirera dans un délai limité. Si vous n'avez pas demandé cette réinitialisation, ignorez simplement cet email.
        </div>

        <p style="font-size: 12px; color: #999;">
            Pour des raisons de sécurité, ne partagez jamais ce lien avec quelqu'un d'autre.
        </p>

        <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>