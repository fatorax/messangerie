window.openCreateChannelModal = function () {
    document.querySelector('.createChannelModal').classList.add('active');
    document.querySelector('.createChannelModal form').reset();
};

window.closeCreateChannelModal = function () {
    document.querySelector('.createChannelModal').classList.remove('active');
};

window.createChannel = async function () {
    const form = document.querySelector('.createChannelModal form');
    const name = form.name.value.trim();
    const errorSpan = document.querySelector('.createChannelModal form .error-name');

    if (!name) {
        errorSpan.classList.remove('hidden');
        errorSpan.textContent = 'Le nom du canal est requis.';
        return;
    }

    try {
        // Envoi de la requête vers ta route Laravel
        const response = await fetch('/channels/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name }),
        });

        // Vérifie la réponse
        if (!response.ok) {
            const errorData = await response.json();

            if (errorData.errors?.name) {
                errorSpan.textContent = errorData.errors.name[0];
            } else {
                errorSpan.textContent = errorData.message || `Erreur HTTP ${response.status}`;
            }

            return; // stoppe l’exécution
        }

        // Récupère les données renvoyées par Laravel
        const data = await response.json();

        // Réinitialise le formulaire et ferme le modal
        form.reset();
        closeCreateChannelModal();

        
        // (Optionnel) Mets à jour l’UI dynamiquement
        const list = document.querySelector('#channelsPublicList');
        if (list && data.channel) {
            const a = document.createElement('a');
            a.href = '/channels/' + data.channel.id;
            a.classList.add('link');
            const div = document.createElement('div');
            div.classList.add('picture');
            const img = document.createElement('img');
            img.src = 'https://picsum.photos/seed/picsum/200/300';
            div.appendChild(img);
            a.appendChild(div);
            const p = document.createElement('p');
            p.textContent = data.channel.name;
            a.appendChild(p);
            list.appendChild(a);
        }

    } catch (error) {
        errorSpan.classList.remove('hidden');
        errorSpan.textContent = 'Une erreur est survenue lors de la création du canal.';
    }
};
