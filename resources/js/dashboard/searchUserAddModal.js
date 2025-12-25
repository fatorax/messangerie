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
        const response = await fetch('/friend-request/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ receiver_id: search }),
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

    } catch (error) {
        errorName.classList.remove('hidden');
        errorName.textContent = 'Une erreur est survenue lors de la création du canal.';
    }
};