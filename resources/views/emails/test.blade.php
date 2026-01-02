<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations de connexion des deux comptes de test</title>
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
            background-color: #3498db;
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
        .accounts {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .accounts ul {
            margin: 10px 0;
            padding: 0 0 0 20px;
        }
        .accounts li {
            margin: 8px 0;
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
            background-color: #3498db;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
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
        <h1>🔐 Comptes de test</h1>
    </div>
    <div class="content">
        <p>Bonjour,</p>

        <p>Vous trouverez ci-dessous les informations de connexion de vos deux comptes de test :</p>

        <div class="accounts">
            <strong>Compte 1 :</strong>
            <ul>
                <li>Email : <strong>{{ $users[0]['email'] }}</strong></li>
                <li>Mot de passe : <strong>{{ $users[0]['password'] }}</strong></li>
            </ul>
        </div>

        <div class="accounts">
            <strong>Compte 2 :</strong>
            <ul>
                <li>Email : <strong>{{ $users[1]['email'] }}</strong></li>
                <li>Mot de passe : <strong>{{ $users[1]['password'] }}</strong></li>
            </ul>
        </div>

        <div class="warning">
            <strong>⚠️ Attention :</strong> Ces comptes de test ont une durée de vie de <strong>24 heures</strong>. Passé ce délai, ils seront automatiquement supprimés.
        </div>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ route('login') }}" class="btn">Se connecter</a>
        </p>

        <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>