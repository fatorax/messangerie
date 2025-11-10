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
        <h1>Rejouigner le chat</h1>
        <p>Bienvenue sur le site de {{ config('app.name') }}</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <label for="">Mot de passe</label>
                <input type="password" name="password" placeholder="Mot de passe">
            </div>
            <a href="{{ route('forgot-password') }}" class="forgot-password">Mot de passe oublié ?</a>
            <button type="submit">Connexion</button>
        </form>
        <p>Vous n'avez pas de compte ? <a href="{{ route('register') }}">Inscrivez-vous</a></p>
    </div>
</body>
</html>