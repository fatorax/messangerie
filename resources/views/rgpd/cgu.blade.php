<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Conditions Générales d'Utilisation - {{ config('app.name') }}</title>
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
        <h1>Conditions Générales d'Utilisation</h1>
        <h2>1. Objet</h2>
        <pre>
Les présentes conditions régissent l'utilisation du site de messagerie instantanée {{ config('app.name') }}.
        </pre>
        <h2>2. Accès au service</h2>
        <pre>
Le service est accessible gratuitement à tout utilisateur disposant d'un accès à Internet. L'utilisateur est responsable de son équipement et de sa connexion.
        </pre>
        <h2>3. Compte utilisateur</h2>
        <pre>
L'utilisateur s'engage à fournir des informations exactes lors de la création de son compte et à ne pas usurper l'identité d'autrui.
        </pre>
        <h2>4. Comportement des utilisateurs</h2>
        <pre>
Il est interdit d'utiliser le service à des fins illicites, diffamatoires, ou contraires à l'ordre public. Tout abus pourra entraîner la suppression du compte.
        </pre>
        <h2>5. Données personnelles</h2>
        <pre>
Les données collectées sont utilisées uniquement pour la gestion du service. Conformément à la loi et au RGPD, vous disposez d'un droit d'accès, de rectification et de suppression de vos données (contact : bellini.romain35@gmail.com).
        </pre>
        <h2>6. Propriété intellectuelle</h2>
        <pre>
Le contenu du site est protégé par le droit d'auteur. Toute reproduction sans autorisation est interdite.
        </pre>
        <h2>7. Responsabilité</h2>
        <pre>
L'éditeur ne saurait être tenu responsable des dommages directs ou indirects liés à l'utilisation du service.
        </pre>
        <h2>8. Modification des CGU</h2>
        <pre>
L'éditeur se réserve le droit de modifier les présentes CGU à tout moment. Les utilisateurs seront informés en cas de modification.
        </pre>
        <h2>9. Contact</h2>
        <pre>
Pour toute question, contactez : bellini.romain35@gmail.com
        </pre>
    </div>
</body>
</html>
