<h1>Vérification de votre adresse email</h1>

<p>Bonjour {{ $user->username }},</p>

<p>Pour vérifier votre adresse email, cliquez sur le lien ci-dessous :</p>

<a href="{{ route('verify-email', [ 'email' => urlencode($user->email), 'hash' => $user->verify_token ]) }}">
    Vérifier mon adresse email
</a>

<p>
    Si le lien ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :
    {{ route('verify-email', [ 'email' => urlencode($user->email), 'hash' => $user->verify_token ]) }}
</p>

<p>Si vous n'avez pas créé de compte, aucune action n'est requise.</p>
<p>Cordialement,</p>
<p>L'équipe {{ config('app.name') }}</p>