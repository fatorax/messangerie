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


