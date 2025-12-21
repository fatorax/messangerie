<h1>Réinitialisation de votre mot de passe</h1>

<p>Bonjour {{ $user->username }},</p>

<p>Vous avez demandé une réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour le modifier :</p>

<a href="{{ route('reset-password', [
    'email' => urlencode($email), 
    'token' => $token
]) }}">
    Réinitialiser mon mot de passe
</a>

<p>
    Si le lien ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :
    {{ route('reset-password', [
        'email' => urlencode($email), 
        'token' => $token
    ]) }}
</p>

<p>Si vous n'avez pas demandé cette réinitialisation, aucune action n'est requise.</p>
<p>Cordialement,</p>
<p>L'équipe {{ config('app.name') }}</p>