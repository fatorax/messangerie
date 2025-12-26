window.openFriendRequestViewModal = async function () {
    const modal = document.querySelector('.FriendRequestViewModal');
    modal.classList.add('active');
    
    // Charger les demandes d'amis
    await loadPendingFriendRequests();
};

window.closeFriendRequestViewModal = function () {
    document.querySelector('.FriendRequestViewModal').classList.remove('active');
};

async function loadPendingFriendRequests() {
    try {
        const response = await fetch('/friend-request/pending', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            console.error('Erreur lors du chargement des demandes d\'amis');
            return;
        }

        const data = await response.json();
        displayFriendRequests(data.requests);

    } catch (error) {
        console.error('Une erreur est survenue:', error);
    }
}

function displayFriendRequests(requests) {
    const usersContainer = document.querySelector('.FriendRequestViewModal .users');
    usersContainer.innerHTML = '';

    if (requests.length === 0) {
        usersContainer.innerHTML = '<p style="text-align: center; padding: 20px;">Aucune demande d\'ami en attente</p>';
        return;
    }

    requests.forEach(request => {
        const sender = request.sender;

        const userDiv = document.createElement('div');
        userDiv.classList.add('user');
        userDiv.setAttribute('data-request-id', request.id);

        const pictureDiv = document.createElement('div');
        pictureDiv.classList.add('picture');
        const img = document.createElement('img');
        img.src = 'https://picsum.photos/seed/picsum/200/300';
        img.alt = `Image de profil de ${sender.username}`;
        pictureDiv.appendChild(img);

        const nameP = document.createElement('p');
        nameP.textContent = sender.username;

        const acceptBtn = document.createElement('button');
        acceptBtn.classList.add('accept');
        acceptBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>';
        acceptBtn.addEventListener('click', async () => {
            await acceptFriendRequest(request.id, userDiv);
        });

        const rejectBtn = document.createElement('button');
        rejectBtn.classList.add('reject');
        rejectBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';
        rejectBtn.addEventListener('click', async () => {
            await rejectFriendRequest(request.id, userDiv);
        });

        userDiv.appendChild(pictureDiv);
        userDiv.appendChild(nameP);
        userDiv.appendChild(acceptBtn);
        userDiv.appendChild(rejectBtn);

        usersContainer.appendChild(userDiv);
    });
}

async function acceptFriendRequest(requestId, element) {
    try {
        const response = await fetch('/friend-request/accept', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ request_id: requestId }),
        });

        if (!response.ok) {
            const error = await response.json();
            alert(error.message || 'Erreur lors de l\'acceptation de la demande');
            return;
        }

        element.remove();
        
        Swal.fire({
            icon: 'success',
            title: 'Demande acceptée',
            text: 'Un channel privé a été créé',
            timer: 2000,
        });

        closeFriendRequestViewModal();

        await loadPendingFriendRequests();

    } catch (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    }
}

async function rejectFriendRequest(requestId, element) {
    try {
        const response = await fetch('/friend-request/reject', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ request_id: requestId }),
        });

        if (!response.ok) {
            const error = await response.json();
            alert(error.message || 'Erreur lors du rejet de la demande');
            return;
        }

        element.remove();
        
        Swal.fire({
            icon: 'success',
            title: 'Demande rejetée',
            timer: 2000,
        });

        await loadPendingFriendRequests();

    } catch (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    }
}