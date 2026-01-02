<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes de démonstration supprimés</title>
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
        .accounts {
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
        <h1>🗑️ Comptes supprimés</h1>
    </div>
    <div class="content">
        <p>Bonjour,</p>
        
        <p>Nous vous informons que vos comptes de démonstration ont été automatiquement supprimés après leur période d'expiration de 24 heures.</p>
        
        <div class="accounts">
            <strong>Comptes supprimés :</strong>
            <ul>
                <li>{{ $username1 }}</li>
                <li>{{ $username2 }}</li>
            </ul>
        </div>
        
        <p>Toutes les conversations et messages associés à ces comptes ont également été supprimés.</p>
        
        <p>Si vous souhaitez continuer à tester notre application, vous pouvez créer de nouveaux comptes de démonstration à tout moment.</p>
        
        <p>Cordialement,<br>L'équipe</p>
    </div>
    <div class="footer">
        <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
    </div>
</body>
</html>
