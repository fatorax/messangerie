<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages - Administration</title>
    @vite(['resources/scss/admin.scss'])
</head>
<body>
    <div class="admin-container">
        {{-- Sidebar --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>Admin Panel</h1>
            </div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin') }}" class="nav-item">
                    <i class="fa-solid fa-gauge"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users') }}" class="nav-item">
                    <i class="fa-solid fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('admin.channels') }}" class="nav-item">
                    <i class="fa-solid fa-hashtag"></i>
                    <span>Channels</span>
                </a>
                <a href="{{ route('admin.conversations') }}" class="nav-item">
                    <i class="fa-solid fa-comments"></i>
                    <span>Conversations</span>
                </a>
                <a href="{{ route('admin.messages') }}" class="nav-item active">
                    <i class="fa-solid fa-message"></i>
                    <span>Messages</span>
                </a>
                <div class="nav-divider"></div>
                <a href="{{ route('dashboard') }}" class="nav-item">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Retour à l'app</span>
                </a>
            </nav>
        </aside>

        {{-- Main content --}}
        <main class="main-content">
            <header class="content-header">
                <h2>Gestion des messages</h2>
                <div class="user-info">
                    <span>{{ auth()->user()->username }}</span>
                    <img src="{{ asset('storage/users/' . auth()->user()->avatar) }}" alt="Avatar">
                </div>
            </header>

            <div class="content-body">
                @if (session('success'))
                    <div class="alert success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="table-toolbar">
                    <div class="search-box">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Rechercher..." onkeyup="filterTable()">
                    </div>
                    <div class="filter-box">
                        <select id="typeFilter" onchange="filterTable()">
                            <option value="">Tous les types</option>
                            <option value="global">Channel public</option>
                            <option value="private">Conversation privée</option>
                        </select>
                    </div>
                </div>

                <div class="table-container">
                    <table class="admin-table" id="dataTable">
                        <thead>
                            <tr>
                                <th class="sortable" data-sort="id">ID <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="author">Auteur <i class="fa-solid fa-sort"></i></th>
                                <th>Conversation</th>
                                <th>Contenu</th>
                                <th class="sortable" data-sort="date">Envoyé le <i class="fa-solid fa-sort"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $message)
                                <tr data-id="{{ $message->id }}" data-author="{{ strtolower($message->user->username ?? '') }}" data-content="{{ strtolower($message->content) }}" data-type="{{ $message->conversation->type ?? '' }}" data-date="{{ $message->created_at->timestamp }}">
                                    <td>{{ $message->id }}</td>
                                    <td>
                                        <div class="user-cell">
                                            <img src="{{ asset('storage/users/' . ($message->user->avatar ?? 'default.webp')) }}" alt="Avatar" class="table-avatar-small">
                                            <span>{{ $message->user->username ?? 'Supprimé' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($message->conversation)
                                            @if ($message->conversation->type === 'global')
                                                <span class="badge global">{{ $message->conversation->name }}</span>
                                            @else
                                                <span class="badge private">Privée</span>
                                            @endif
                                        @else
                                            <span class="badge deleted">Supprimée</span>
                                        @endif
                                    </td>
                                    <td class="message-content">{{ Str::limit($message->content, 60) }}</td>
                                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="actions">
                                        <button class="btn-view" data-content="{{ base64_encode($message->content) }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <form action="{{ route('admin.messages.delete', $message->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            <button type="submit" class="btn-delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty">Aucun message</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $messages->links() }}
                </div>
            </div>
        </main>
    </div>

    {{-- Modal vue message --}}
    <div id="viewMessageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Contenu du message</h3>
                <button class="close-modal" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p id="messageContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeViewModal()">Fermer</button>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function closeViewModal() {
            document.getElementById('viewMessageModal').classList.remove('active');
        }

        document.getElementById('viewMessageModal').addEventListener('click', function(e) {
            if (e.target === this) closeViewModal();
        });

        // Gestion des boutons view avec data-content (base64)
        document.querySelectorAll('.btn-view').forEach(btn => {
            btn.addEventListener('click', function() {
                const content = atob(this.dataset.content);
                document.getElementById('messageContent').textContent = content;
                document.getElementById('viewMessageModal').classList.add('active');
            });
        });

        // SweetAlert2 pour les suppressions
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Supprimer ce message ?',
                    text: 'Cette action est irréversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ed4245',
                    cancelButtonColor: '#3a3a5a',
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler',
                    background: '#23233f',
                    color: '#ffffff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Recherche et filtrage
        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const typeValue = document.getElementById('typeFilter').value;
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const author = row.dataset.author || '';
                const content = row.dataset.content || '';
                const id = row.dataset.id || '';
                const type = row.dataset.type || '';

                const matchSearch = author.includes(searchValue) || content.includes(searchValue) || id.includes(searchValue);
                const matchType = !typeValue || type === typeValue;

                row.style.display = (matchSearch && matchType) ? '' : 'none';
            });
        }

        // Tri des colonnes
        let sortDirection = {};
        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function() {
                const sortKey = this.dataset.sort;
                const tbody = document.querySelector('#dataTable tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                sortDirection[sortKey] = !sortDirection[sortKey];

                rows.sort((a, b) => {
                    let aVal = a.dataset[sortKey] || '';
                    let bVal = b.dataset[sortKey] || '';

                    if (['id', 'date'].includes(sortKey)) {
                        aVal = parseInt(aVal);
                        bVal = parseInt(bVal);
                    }

                    if (aVal < bVal) return sortDirection[sortKey] ? -1 : 1;
                    if (aVal > bVal) return sortDirection[sortKey] ? 1 : -1;
                    return 0;
                });

                rows.forEach(row => tbody.appendChild(row));

                document.querySelectorAll('.sortable i').forEach(i => i.className = 'fa-solid fa-sort');
                this.querySelector('i').className = sortDirection[sortKey] ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down';
            });
        });
    </script>
</body>
</html>
