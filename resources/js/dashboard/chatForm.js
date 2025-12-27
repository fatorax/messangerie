document.addEventListener("DOMContentLoaded", () => {
    const textarea = document.getElementById('message-input');
    const form = document.getElementById('chat-form');
    const chatBox = document.getElementById('chat-box');

    if (!textarea || !form) return;

    // Récupère l'URL depuis un attribut data du formulaire
    const messageRoute = form.dataset.route;

    // Envoi du message avec fetch
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let input = textarea;
        let conversationId = document.querySelector('input[name="conversation_id"]').value;

        fetch(messageRoute, {
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

    // Envoi avec Enter (sauf Shift+Enter)
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
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
