<footer>
    <form id="chat-form">
        @csrf
        <button type="button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-link-icon lucide-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </button>
        <input type="hidden" name="conversation_id" value="{{ $conversationView->id }}">
        <input type="text" name="content" id="message-input" placeholder="Votre message ...">
        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-icon lucide-send"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/></svg>
        </button>
    </form>
</footer>

<script src="https://js.pusher.com/8.4/pusher.min.js"></script>
<script>
    // Préférer Laravel Echo (utilise axios.withCredentials=true). N'utiliser Pusher direct
    // que comme fallback si Echo n'est toujours pas chargé après un court délai.
    console.log('Pusher/Echo init (deferred), Echo present:', !!window.Echo);

    var channel = null;
    function initPusherFallback() {
        if (window.Echo) {
            console.log('Echo is present, skipping Pusher fallback.');
            return;
        }
        // Affichage console
        Pusher.logToConsole = false;

        // Connexion Pusher (authEndpoint pour canaux privés)
        var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            forceTLS: true,
            authEndpoint: '{{ url('/broadcasting/auth') }}',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        });

        // Abonnement au channel (fallback)
        channel = pusher.subscribe('private-conversation.{{ $conversationView->id }}');
    }

    // Delay initialization slightly so compiled assets (Echo) can load first.
    setTimeout(initPusherFallback, 500);

    // Debug: log cookies and test broadcasting auth endpoint
    console.log('document.cookie:', document.cookie);
    (function testBroadcastAuth() {
        const socketId = (window.Echo && window.Echo.socketId && window.Echo.socketId()) || '';
        fetch('{{ url('/broadcasting/auth') }}', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ socket_id: socketId, channel_name: `private-conversation.{{ $conversationView->id }}` })
        }).then(async r => {
            console.log('/broadcasting/auth test status:', r.status);
            try { console.log('/broadcasting/auth test body:', await r.text()); } catch(e){}
        }).catch(err => console.error('broadcasting/auth test error', err));
    })();

        // debug: état connexion et subscription
    
    // debug: état connexion et subscription (gardez les checks car fallback Pusher est différé)
    if (typeof pusher !== 'undefined' && pusher && pusher.connection) {
        try {
            console.log('Pusher state:', pusher.connection.state);
            pusher.connection.bind('connected', function() {
                console.log('Pusher connected, socket id:', pusher.connection.socket_id || pusher.connection.socketId);
            });
            pusher.connection.bind('error', function(err) {
                console.error('Pusher connection error:', err);
            });
        } catch (e) { console.warn('Pusher debug bind failed', e); }
    } else {
        console.log('Pusher instance not initialized yet.');
    }

    if (channel) {
        try {
            channel.bind('pusher:subscription_succeeded', function() {
                console.log('Subscribed to channel:', channel.name);
            });
            channel.bind('pusher:subscription_error', function(status) {
                console.error('Subscription error for channel', channel.name, status);
            });
        } catch (e) { console.warn('Channel debug bind failed', e); }
    }

    // debug: log all incoming events on Pusher connection (if available)
    if (typeof pusher !== 'undefined' && pusher) {
        if (typeof pusher.bind_global === 'function') {
            pusher.bind_global(function(eventName, data) {
                console.log('Pusher global event:', eventName, data);
            });
        } else if (typeof pusher.bind_all === 'function') {
            pusher.bind_all(function(eventName, data) {
                console.log('Pusher global event (bind_all):', eventName, data);
            });
        }
    }

    // handler réutilisable pour ajouter le message au DOM (sécurisé)
    function appendMessage(data) {
        console.log('Pusher message received:', data);
        const msg = data?.message;
        const chatBox = document.getElementById('chat-box');
        if (!msg || !chatBox) return;

        function formatDate(input) {
            if (!input) return '';
            let s = String(input).trim();
            if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:?\d{0,2}$/.test(s)) s = s.replace(' ', 'T');
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return '';
            const pad = n => String(n).padStart(2, '0');
            return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${pad(d.getFullYear())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
        }

        const wrapper = document.createElement('div');
        wrapper.classList.add('chat-box-message');
        if (msg.user_id == {{ $user->id }}) wrapper.classList.add('active');

        const picDiv = document.createElement('div');
        picDiv.className = 'chat-box-picture';
        const img = document.createElement('img');
        img.src = 'https://picsum.photos/seed/picsum/200/300';
        img.alt = 'Image de profil';
        picDiv.appendChild(img);

        const infoDiv = document.createElement('div');
        infoDiv.className = 'chat-box-informations';

        const nameDiv = document.createElement('div');
        nameDiv.className = 'chat-box-informations-name';
        const h2 = document.createElement('h2');
        h2.textContent = msg.user?.username ?? msg.user_username ?? msg.user_id ?? 'Utilisateur';
        const pDate = document.createElement('p');
        pDate.className = 'date';
        pDate.textContent = formatDate(msg.created_at);
        nameDiv.appendChild(h2);
        nameDiv.appendChild(pDate);

        const msgContainer = document.createElement('div');
        msgContainer.className = 'chat-box-informations-message';
        const p = document.createElement('p');
        p.textContent = msg.content ?? '';
        msgContainer.appendChild(p);

        infoDiv.appendChild(nameDiv);
        infoDiv.appendChild(msgContainer);

        wrapper.appendChild(picDiv);
        wrapper.appendChild(infoDiv);

        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Utiliser le handler pour Pusher fallback si présent
    if (channel) channel.bind('MessageSent', appendMessage);

    // Si Laravel Echo est chargé (recommandé), s'abonner au canal privé
    var conversationId = {{ $conversationView->id }};
    if (window.Echo) {
        window.Echo.private(`private-conversation.${conversationId}`)
            .listen('MessageSent', appendMessage);
    }

    // Envoi via fetch
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let input = document.getElementById('message-input');
        let conversationId = document.querySelector('input[name="conversation_id"]').value;

        fetch('{{ route("message-sent") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                content: input.value,
                conversation_id: conversationId
            })
        }).then(res => {
            if (!res.ok) throw new Error('Erreur serveur');
            input.value = '';
        }).catch(err => console.error(err));
    });

    // Scroll initial en bas
    document.addEventListener("DOMContentLoaded", () => {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>


