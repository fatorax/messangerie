<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations de connexion des deux comptes de test</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 10px;">
        <h1 style="color: #2c3e50; margin-bottom: 20px;">Informations de connexion des deux comptes de test</h1>
        <p>Bonjour,</p>

        <p>Vous trouverez ci-dessous les informations de connexion de vos deux comptes de test :</p>

        <ul style="margin: 20px 0; padding: 0 0 0 20px; list-style-type: disc;">
            <li>Email compte 1 : <span style="font-weight: bold; color: #3498db;">{{ $users[0]['email'] }}</span></li>
            <li>Mot de passe compte 1 : <span style="font-weight: bold; color: #3498db;">{{ $users[0]['password'] }}</span></li>
            <li>Email compte 2 : <span style="font-weight: bold; color: #3498db;">{{ $users[1]['email'] }}</span></li>
            <li>Mot de passe compte 2 : <span style="font-weight: bold; color: #3498db;">{{ $users[1]['password'] }}</span></li>
        </ul>

        <p style="background-color: #fff3cd; border: 1px solid #ffc107; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <strong>⚠️ Attention :</strong> Ces comptes de test ont une durée de vie de <strong>24 heures</strong>. Passé ce délai, ils seront automatiquement supprimés.
        </p>

        <p style="text-align: center; margin: 25px 0;">
            <a href="{{ route('login') }}" style="background-color: #3498db; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Se connecter</a>
        </p>

        <p>Cordialement,<br>L'équipe {{ config('app.name') }}</p>
    </div>
</body>
</html>