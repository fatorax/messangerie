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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([
        'resources/scss/pages/dashboard/dashboard.scss',
        'resources/scss/pages/dashboard/modal/app.scss',
        'resources/js/dashboard/CreateChannelModal.js',
        'resources/js/dashboard/EditChannelModal.js',
        'resources/js/dashboard/DeleteChannelModal.js',
        'resources/js/dashboard/sendMessageChannel.js',
        'resources/js/dashboard/searchUserAddModal.js',
        'resources/js/app.js',
    ])
</head>
<body>
    @include('chat.modal.createChannelModal')
    @include('chat.modal.editChannelModal')
    @include('chat.modal.searchUserAddModal')
    @include('chat.layout.nav')
    <div class="container">
        @include('chat.layout.header')
        @include('chat.layout.content')
        @include('chat.layout.footer')
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