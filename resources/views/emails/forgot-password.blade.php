<a href="{{ route('reset-password', [
    'email' => urlencode($email), 
    'token' => $token
]) }}">
    Modification du mot de passe
</a>