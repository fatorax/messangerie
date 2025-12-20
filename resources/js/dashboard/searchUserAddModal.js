window.openSearchUserAddModal = function () {
    document.querySelector('.searchUserAddModal').classList.add('active');
    document.querySelector('.searchUserAddModal form').reset();
};

window.closeSearchUserAddModal = function () {
    document.querySelector('.searchUserAddModal').classList.remove('active');
};

window.searchUserAddModal = async function () {
    const searchUserInput = document.querySelector('.searchUserAddModal input[name="name"]');
    const search = searchUserInput.value.trim();
    const errorName = document.querySelector('.searchUserAddModal form .error-name');
    
    if (!search) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Le nom du canal est requis.';
        return;
    }

    try {
        const response = await fetch('/users/search', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ search }),
        });

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
        
        if(!data.success) {
            errorName.classList.remove('hidden');
            errorName.textContent = data.message;
            return;
        }

        closeSearchUserAddModal();

        const list = document.querySelector('#channelsPrivateList');
        if (list && data.channel) {
            const a = document.createElement('a');
            a.href = '/channels/' + data.channel.id;
            a.classList.add('link');
            const divGlobal = document.createElement('div');
            divGlobal.classList.add('picture');
            const img = document.createElement('img');
            img.src = 'https://picsum.photos/seed/picsum/200/300';
            divGlobal.appendChild(img);
            const div = document.createElement('div');
            if (data.user.is_connected == true) {
                div.classList.add('connected');
                div.classList.add('online');
            }
            divGlobal.appendChild(div);
            a.appendChild(divGlobal);
            const p = document.createElement('p');
            p.textContent = data.user.username;
            a.appendChild(p);
            list.appendChild(a);
        }

    } catch (error) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Une erreur est survenue lors de la création du canal.';
    }
};