<a href="{{ route('verify-email', [
    'email' => urlencode($user->email), 
    'hash' => $user->verify_token
]) }}">
    Vérification de votre adresse email
</a>