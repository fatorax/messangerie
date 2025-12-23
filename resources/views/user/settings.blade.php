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
        'resources/js/app.js',
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
        <a href="{{ route('dashboard') }}" class="back">Retour au tableau de bord</a>
        <h1>Paramètres</h1>
        <form method="POST" action="{{ route('user.settings.update') }}">
            @csrf
            <h2>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Profile
            </h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" value="{{ $user->email }}" placeholder="Email" disabled>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" name="username" value="{{ $user->username }}" placeholder="Nom d'utilisateur">
                    @error('username')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">Nom</label>
                    <input type="text" name="firstname" value="{{ $user->firstname }}" placeholder="Nom">
                @error('firstname')
                    <span class="error">{{ $message }}</span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="lastname">Prénom</label>
                    <input type="text" name="lastname" value="{{ $user->lastname }}" placeholder="Prénom">
                    @error('lastname')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit">Sauvegarder Profile</button>
        </form>
        <div class="links">
            <a href="{{ route('user.password.change') }}" class="info">Modifier le mot de passe</a>
            <a href="{{ route('user.delete') }}" id="delete-account-link" class="danger">Supprimer le compte</a>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteLink = document.getElementById('delete-account-link');
            if (deleteLink) {
                deleteLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Êtes-vous sûr de vouloir supprimer votre compte ?",
                        text: "Cette action est irréversible !",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Oui, supprimer",
                        cancelButtonText: "Annuler",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Rediriger vers la route de suppression du compte
                            window.location.href = "{{ route('user.delete') }}";
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>