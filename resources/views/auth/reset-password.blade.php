<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/scss/pages/login.scss'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Modification du mot de passe</h1>
        <p>Vous y êtes presque, il vous suffit de renseigner votre nouveau mot de passe ci-dessous</p>
        <form method="POST" action="{{ route('reset-password', ['email' => urlencode($email), 'token' => $token]) }}">
            @csrf
            <div class="form-group">
                <label for="">Mot de passe</label>
                <input type="password" name="password" placeholder="Mot de passe">
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Confirmer le mot de passe</label>
                <input type="password" name="password-confirm" placeholder="Confirmer le mot de passe">
                @error('password-confirm')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Modifier mon mot de passe</button>
        </form>
        <p>Vous avez retrouvé votre mot de passe ? <a href="{{ route('login') }}">Connexion</a></p>
    </div>
</body>
</html>