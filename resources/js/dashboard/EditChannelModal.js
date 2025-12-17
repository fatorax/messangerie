window.openeditChannelModal = function () {
    document.querySelector('.editChannelModal').classList.add('active');
    document.querySelector('.editChannelModal form').reset();
};

window.closeEditChannelModal = function () {
    document.querySelector('.editChannelModal').classList.remove('active');
};

window.editChannel = async function () {
    const form = document.querySelector('.editChannelModal form');
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

            return;
        }

        const data = await response.json();

        form.reset();
        closeChannelModal();

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
        errorName.classList.remove('hidden');
        errorName.textContent = 'Une erreur est survenue lors de la création du canal.';
    }
};