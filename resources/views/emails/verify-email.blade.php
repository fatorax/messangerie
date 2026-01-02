<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre adresse email</title>
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
            background-color: #27ae60;
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
            border-left: 4px solid #27ae60;
        }
        .btn {
            display: inline-block;
            background-color: #27ae60;
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
            color: #27ae60;
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
        <h1>✉️ Vérification de votre email</h1>
    </div>
    <div class="content">
        <p>Bonjour {{ $user->username }},</p>

        <p>Merci de vous être inscrit sur {{ config('app.name') }}. Pour activer votre compte, veuillez vérifier votre adresse email en cliquant sur le bouton ci-dessous :</p>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ route('verify-email', ['email' => urlencode($user->email), 'hash' => $user->verify_token]) }}" class="btn">
                Vérifier mon adresse email
            </a>
        </p>

        <div class="info-box">
            <p class="link-fallback">
                Si le bouton ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :<br>
                <a href="{{ route('verify-email', ['email' => urlencode($user->email), 'hash' => $user->verify_token]) }}">{{ route('verify-email', ['email' => urlencode($user->email), 'hash' => $user->verify_token]) }}</a>
            </p>
        </div>

        <p style="font-size: 12px; color: #999;">
            Si vous n'avez pas créé de compte sur {{ config('app.name') }}, vous pouvez ignorer cet email.
        </p>

        <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>