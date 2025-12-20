<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite([
        'resources/scss/pages/dashboard/dashboard.scss',
    ])
</head>
<body>
    @if(session('success'))
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès !',
            text: "{{ session('success') }}",
        });
        </script>
    @endif

    @if(session('error'))
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur !',
            text: "{{ session('error') }}",
        });
        </script>
    @endif
    <section class="settings">
        <a href="{{ route('settings') }}" class="back">Retour aux paramètres</a>
        <h1>Changer le mot de passe</h1>
        <form method="POST" action="{{ route('user.password.update') }}">
            @csrf
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Nouveau mot de passe
            </h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" required minlength="8" placeholder="Nouveau mot de passe">
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8" placeholder="Confirmer le mot de passe">
                </div>
            </div>
            <button type="submit">Changer le mot de passe</button>
        </form>
        <div class="links">
            <a href="{{ route('settings') }}" class="info">Retour aux paramètres</a>
        </div>
    </section>
</body>
</html>
