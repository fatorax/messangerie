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

window.previewChannelImage = function (input) {
    const preview = document.getElementById('channel-image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu de l'image">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
};

window.deleteChannelImage = async function (channelId) {
    if (!confirm('Voulez-vous vraiment supprimer l\'image du channel ?')) {
        return;
    }

    try {
        const response = await fetch('/channels/delete-image', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: channelId }),
        });

        if (!response.ok) {
            const errorData = await response.json();
            alert(errorData.message || 'Erreur lors de la suppression de l\'image');
            return;
        }

        // Recharger la page pour voir les changements
        window.location.reload();

    } catch (error) {
        alert('Une erreur est survenue lors de la suppression de l\'image.');
    }
};

window.editChannel = async function () {
    const form = document.querySelector('.editChannelModal form');
    const id = form.id.value;
    const name = form.name.value.trim();
    const imageInput = form.querySelector('input[name="image"]');
    const errorName = document.querySelector('.editChannelModal form .error-name');
    const errorImage = document.querySelector('.editChannelModal form .error-image');

    if (!name) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Le nom du canal est requis.';
        return;
    }

    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('name', name);
        if (imageInput.files[0]) {
            formData.append('image', imageInput.files[0]);
        }

        const response = await fetch('/channels/edit', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        });

        // Vérifie la réponse
        if (!response.ok) {
            const errorData = await response.json();

            if (errorData.errors?.name) {
                errorName.classList.remove('hidden');
                errorName.textContent = errorData.errors.name[0];
            } else if (errorData.errors?.image) {
                errorImage.classList.remove('hidden');
                errorImage.textContent = errorData.errors.image[0];
            } else {
                errorName.classList.remove('hidden');
                errorName.textContent = errorData.message || `Erreur HTTP ${response.status}`;
            }

            return;
        }

        const data = await response.json();

        // Met à jour l'image dans la liste des channels si elle a changé
        if (data.channel && data.channel.image) {
            const channelLink = document.querySelector(`.link[data-conversation-id="${id}"] img`);
            if (channelLink) {
                channelLink.src = `/storage/channels/${data.channel.image}?t=${Date.now()}`;
            }
        }

        form.reset();
        closeEditChannelModal();
        
        // Recharger la page pour voir les changements
        window.location.reload();

    } catch (error) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Une erreur est survenue lors de la modification du canal.';
    }
};