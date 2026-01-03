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
    @if (session('success'))
        <div class="container">
            <p class="success">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="container">
            <p class="error">{{ session('error') }}</p>
        </div>
    @endif
    <div class="container rgpd">
		<a href="{{ route('index') }}">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
			Retour a la page d'accueil
        </a>
        <h1>Mentions légales</h1>
        <h2>Éditeur du site :</h2>
        <pre>
Nom : Bellini Romain
Adresse e-mail : bellini.romain35@gmail.com
        </pre>
        <h2>Hébergement :</h2>
        <pre>
Ce site est hébergé par :
Hostinger International Ltd
61 Lordou Vironos Street
6023 Larnaca – Chypre
https://www.hostinger.fr
        </pre>
        <h2>Propriété intellectuelle :</h2>
        <pre>
Le contenu de ce site (textes, images, etc.) est protégé par le droit d’auteur. Toute reproduction sans autorisation est interdite.
        </pre>
        <h2>Données personnelles :</h2>
        <pre>
Les informations collectées via le site sont destinées uniquement à la gestion des messages instantanés. Conformément à la loi « Informatique et Libertés » et au RGPD, vous disposez d’un droit d’accès, de rectification et de suppression de vos données. Pour exercer ce droit, contactez bellini.romain35@gmail.com.
        </pre>
        <h2>Responsabilité :</h2>
        <pre>
L’éditeur du site ne saurait être tenu responsable des dommages directs ou indirects liés à l’utilisation du site.
        </pre>
</body>
</html>