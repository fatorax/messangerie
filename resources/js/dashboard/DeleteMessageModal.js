window.deleteMessage = async function (button) {
    const messageDiv = button.closest('.chat-box-message');
    if (!messageDiv) return;

    const result = await Swal.fire({
        title: "Supprimer ce message ?",
        text: "Cette action est irréversible !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Oui, supprimer",
        cancelButtonText: "Annuler",
        reverseButtons: true
    });

    if (!result.isConfirmed) return;

    // Récupérer l'ID du message depuis un attribut data
    const messageId = messageDiv.getAttribute('data-message-id');
    if (!messageId) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "Impossible de trouver l'ID du message",
            timer: 2000,
        });
        return;
    }

    try {
        const response = await fetch('/messages/delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message_id: messageId }),
        });

        if (!response.ok) {
            const error = await response.json();
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: error.message || "Erreur lors de la suppression du message",
                timer: 2000,
            });
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Message supprimé',
            timer: 1500,
        });

    } catch (error) {
        console.error('Erreur:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: "Une erreur est survenue",
            timer: 2000,
        });
    }
};
