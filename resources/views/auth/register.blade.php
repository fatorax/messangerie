<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    @vite([
        'resources/scss/pages/register.scss',
        'resources/js/checkPasswordStrength.js',
        'resources/js/viewPassword.js',
        'resources/js/auth/profilePicturePreview.js',
        ])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <a href="{{ route('index') }}" class="back">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Retour a la page d'accueil
    </a>
    <div class="container">
        <h1>Rejoindre le chat</h1>
        <p>Bienvenue sur le site de {{ config('app.name') }}</p>
        <form method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row center">
                <div class="form-group">
                    <label for="">Image de profil</label>
                    <input type="file" name="profile-picture" accept="image/*" class="profile-picture-input" style="display:none;">
                    <img src="{{ asset('storage/users/default.webp') }}" alt="" class="profil-picture-preview" style="cursor:pointer; width:120px; height:120px; object-fit:cover; border-radius:50%;">
                    @error('profile-picture')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="">Nom</label>
                    <input type="firtName" name="firtName" placeholder="Nom" value="{{ old('firtName') }}" required>
                    @error('firtName')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Prénom</label>
                    <input type="lastName" name="lastName" placeholder="Prénom" value="{{ old('lastName') }}" required>
                    @error('lastName')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="">Pseudonyme</label>
                    <input type="pseudonyme" name="pseudonyme" placeholder="Pseudonyme" value="{{ old('pseudonyme') }}" max="30" required>
                    @error('pseudonyme')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="">Mot de passe</label>
                    <div class="input">
                        <input type="password" name="password" placeholder="Mot de passe" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="view-password" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="hidden-password hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                    </div>
                    <span class="password-level hidden"></span>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Confirmer le mot de passe</label>
                    <div class="input">
                        <input type="password" name="password-confirm" placeholder="Confirmer le mot de passe" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="view-password-confirm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="hidden-password-confirm hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                    </div>
                    @error('password-confirm')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <section class="rgpd-accept">
                <input type="checkbox" name="rgpd" id="rgpd" required>
                <label for="rgpd">J'accepte les <a href="{{ route('cgu') }}">Conditions Générales d'utilisation</a></label>
            </section>
            @error('rgpd')
                <span class="error">{{ $message }}</span>
            @enderror
            <button type="submit">Inscription</button>
        </form>
        <p>Vous avez déjà un compte ? <a href="{{ route('login') }}">Connexion</a></p>
    </div>
</body>
</html>