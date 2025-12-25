window.openeditChannelModal = function () {
    const modal = document.querySelector('.editChannelModal');
    if (modal) {
        modal.classList.add('active');
        const form = modal.querySelector('form');
        if (form) form.reset();
    }
};

window.closeEditChannelModal = function () {
    document.querySelector('.editChannelModal').classList.remove('active');
};

window.editChannel = async function () {
    const form = document.querySelector('.editChannelModal form');
    const id = form.id.value;
    const name = form.name.value.trim();
    const errorName = document.querySelector('.editChannelModal form .error-name');

    if (!name) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Le nom du canal est requis.';
        return;
    }

    try {
        const response = await fetch('/channels/edit', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id, name }),
        });

        // Vérifie la réponse
        if (!response.ok) {
            const errorData = await response.json();

            if (errorData.errors?.name) {
                errorSpan.textContent = errorData.errors.name[0];
            } else {
                errorSpan.textContent = errorData.message || `Erreur HTTP ${response.status}`;
            }

            return;
        }

        const data = await response.json();

        form.reset();
        closeEditChannelModal();

    } catch (error) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Une erreur est survenue lors de la modification du canal.';
    }
};