<footer>
    <form id="chat-form">
        @csrf
        <input type="hidden" name="conversation_id" value="{{ $conversationView->id }}">
        <textarea name="content" id="message-input" rows="1"></textarea>
        <button type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-icon lucide-send"><path d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z"/><path d="m21.854 2.147-10.94 10.939"/></svg>
        </button>
    </form>
</footer>

<script src="https://js.pusher.com/8.4/pusher.min.js"></script>
<script>
    const textarea = document.getElementById('message-input');
    const form = document.getElementById('chat-form');

    // Envoi du message avec fetch
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let input = textarea;
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
            textarea.style.height = 'auto';
        }).catch(err => console.error(err));
    });

    // Envoi avec Enter (sauf Alt+Enter)
    textarea.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
    });

    // Auto-resize du textarea
    textarea.addEventListener('input', () => {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    });

    // Scroll initial en bas
    document.addEventListener("DOMContentLoaded", () => {
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>


