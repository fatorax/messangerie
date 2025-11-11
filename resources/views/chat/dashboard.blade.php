<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/scss/pages/dashboard.scss'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script> <!-- si tu as app.js compilé -->
</head>
<body>
    <nav>
        <header>
            <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
        </header>
        <main>
            <div class="channels">
                <div class="head">
                    <h2>Channels</h2>
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>
                <div class="links">
                    <a href="#" class="link active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash-icon lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                        <p>Géneral</p>
                    </a>
                    <a href="#" class="link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash-icon lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                        <p>Random</p>
                    </a>
                    <a href="#" class="link">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash-icon lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                        <p>Autre</p>
                    </a>
                </div>
            </div>
            <div class="channels">
                <div class="head">
                    <h2>Messages privés</h2>
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </div>
                <div class="links">
                    <a href="#" class="link">
                        <div class="picture">
                            <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                            <div class="connected online"></div>
                        </div>
                        <p>Alex</p>
                    </a>
                    <a href="#" class="link">
                        <div class="picture">
                            <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                            <div class="connected absent"></div>
                        </div>
                        <p>Romain</p>
                    </a>
                    <a href="#" class="link">
                        <div class="picture">
                            <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                            <div class="connected offline"></div>
                        </div>
                        <p>Serena</p>
                    </a>
                </div>
            </div>
        </main>
        <footer>
            <div class="picture">
                <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                <div class="connected"></div>
            </div>
            <div class="name">
                <h2>{{ $user->username }}</h2>
                <p>En ligne</p>
            </div>
        </footer>
    </nav>
    <div class="container">
        <header>
            <div class="left">
                <div class="hashtag">
                    <svg xmlns="http://www.w3.org/2000/svg" class="hashtag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash-icon lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                </div>
                <div class="title">
                    <p class="title">Global</p>
                    <div class="members">
                        <svg xmlns="http://www.w3.org/2000/svg" class="user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <p class="members">1 234 membre en ligne</p>
                    </div>
                </div>
            </div>
            <div class="right">
                <a href="{{ route('settings') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </a>
            </div>
        </header>
        <main>
            <div class="chat-box">
                <div class="chat-box-message">
                    <div class="chat-box-picture">
                        <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                    </div>
                    <div class="chat-box-informations">
                        <div class="chat-box-informations-name">
                            <h2>{{ $user->username }}</h2>
                            <p class="date">10:30</p>
                        </div>
                        <div class="chat-box-informations-message">
                            <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Inventore, eligendi commodi eveniet porro saepe incidunt nostrum praesentium ad labore optio facere dolore fuga quam magnam mollitia distinctio laboriosam et deleniti?</p>
                        </div>
                    </div>
                </div>
                <div class="chat-box-message active">
                    <div class="chat-box-picture">
                        <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                    </div>
                    <div class="chat-box-informations">
                        <div class="chat-box-informations-name">
                            <h2>{{ $user->username }}</h2>
                            <p class="date">10:30</p>
                        </div>
                        <div class="chat-box-informations-message">
                            <p>Lorem ipsum dolor sit amet consectetur, a</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <form action="">
                @csrf
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link-icon lucide-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
                <input type="text" name="content" id="message-input" placeholder="Votre message ...">
                <button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-icon lucide-send"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/></svg>
                </button>
            </form>
        </footer>
    </div>
    {{-- <script>
        // Activer Pusher
        Pusher.logToConsole = true;

        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            forceTLS: true
        });

        // Écouter le canal global
        var channel = pusher.subscribe('global');

        channel.bind('MessageSent', function(data) {
            let chatBox = document.getElementById('chat-box');
            let msg = `<p><strong>${data.message.user_id}:</strong> ${data.message.content}</p>`;
            chatBox.innerHTML += msg;
        });
    </script>

    <div id="chat-box" style="border:1px solid #ccc; padding:10px; height:300px; overflow:auto;"></div>

    <form id="chat-form" method="POST" action="{{ route('messages.store') }}">
        @csrf
        <input type="text" name="content" id="message-input" placeholder="Votre message">
        <button type="submit">Envoyer</button>
    </form>

    <script>
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();

        let input = document.getElementById('message-input');

        fetch('{{ route("messages.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ content: input.value })
        });

        input.value = '';
    }); 
    </script> --}}
</body>
</html>