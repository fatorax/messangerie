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
        <h1>Rejoindre le chat</h1>
        <p>Bienvenue sur le site de {{ config('app.name') }}</p>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="">Nom</label>
                <input type="firtName" name="firtName" placeholder="Nom">
                @error('firtName')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Prénom</label>
                <input type="lastName" name="lastName" placeholder="Prénom">
                @error('lastName')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Pseudonyme</label>
                <input type="pseudonyme" name="pseudonyme" placeholder="Pseudonyme">
                @error('pseudonyme')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="Email">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Mot de passe</label>
                <input type="password" name="password" placeholder="Mot de passe">
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="">Comfirmer le mot de passe</label>
                <input type="password" name="password-confirm" placeholder="Confirmer le mot de passe">
                @error('password-confirm')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <label for="rgpd">J'accepte les <a href="{{ route('pages.show', ['page' => 'cgv']) }}">Conditions Générales de Vente</a></label>
            <input type="checkbox" name="rgpd" id="rgpd">
            @error('rgpd')
                <span class="error">{{ $message }}</span>
            @enderror
            <button type="submit">Inscrption</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="{{ route('login') }}">Connexion</a></p>
    </div>
</body>
</html>