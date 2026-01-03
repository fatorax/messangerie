<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte supprimé</title>
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
            background-color: #dc3545;
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
        .account-info {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
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
        <h1>🗑️ Compte supprimé</h1>
    </div>
    <div class="content">
        <p>Bonjour,</p>
        
        @if($byAdmin)
            <p>Nous vous informons que votre compte a été supprimé par un administrateur.</p>
        @else
            <p>Nous vous confirmons que votre compte a bien été supprimé suite à votre demande.</p>
        @endif

        <div class="account-info">
            <p><strong>Nom d'utilisateur :</strong> {{ $username }}</p>
        </div>

        <p>Toutes vos données personnelles, conversations et messages ont été définitivement supprimés de notre plateforme.</p>

        @if($byAdmin)
            <p>Si vous pensez qu'il s'agit d'une erreur, veuillez contacter notre support.</p>
        @else
            <p>Nous espérons vous revoir bientôt sur notre plateforme !</p>
        @endif

        <p>Cordialement,<br>L'équipe Messangerie</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>
</html>
