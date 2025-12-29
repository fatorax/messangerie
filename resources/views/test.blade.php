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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div class="container login-container">
        <h1>Bienvenue sur le chat</h1>
        <p>Toutes les informations vous seront envoyées par email</p>
        <form method="POST" action="{{ route('test-send') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>