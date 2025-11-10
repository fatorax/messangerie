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
        <h1>Vous avez oublié votre mot de passe ?</h1>
        <p>Pas de panique, il vous suffit de renseigner votre adresse email ci-dessous</p>
        <form method="POST" action="{{ route('forgot-password') }}">
            @csrf
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="Email">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Modifier mon mot de passe</button>
        </form>
        <p>Vous avez retrouvé votre mot de passe ? <a href="{{ route('login') }}">Connexion</a></p>
    </div>
</body>
</html>