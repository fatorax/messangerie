<script src="https://js.pusher.com/8.0/pusher.min.js"></script>
<script src="{{ mix('js/app.js') }}"></script> <!-- si tu as app.js compilé -->

<script>
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
</script>
