<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Conversations privées - Administration</title>
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
                <a href="{{ route('admin.conversations') }}" class="nav-item active">
                    <i class="fa-solid fa-comments"></i>
                    <span>Conversations</span>
                </a>
                <a href="{{ route('admin.messages') }}" class="nav-item">
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
                <h2>Gestion des conversations privées</h2>
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
                        <input type="text" id="searchInput" placeholder="Rechercher par participant..." onkeyup="filterTable()">
                    </div>
                </div>

                <div class="table-container">
                    <table class="admin-table" id="dataTable">
                        <thead>
                            <tr>
                                <th class="sortable" data-sort="id">ID <i class="fa-solid fa-sort"></i></th>
                                <th>Participants</th>
                                <th class="sortable" data-sort="messages">Messages <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="date">Créé le <i class="fa-solid fa-sort"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($conversations as $conversation)
                                <tr data-id="{{ $conversation->id }}" data-participants="{{ strtolower($conversation->users->pluck('username')->join(' ')) }}" data-messages="{{ $conversation->messages->count() }}" data-date="{{ $conversation->created_at->timestamp }}">
                                    <td>{{ $conversation->id }}</td>
                                    <td>
                                        <div class="participants">
                                            @foreach ($conversation->users as $user)
                                                <span class="participant">
                                                    <img src="{{ asset('storage/users/' . $user->avatar) }}" alt="Avatar" class="table-avatar-small">
                                                    {{ $user->username }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>{{ $conversation->messages->count() }}</td>
                                    <td>{{ $conversation->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin.conversation.messages', $conversation->id) }}" class="btn-view" title="Voir les messages">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.conversations.delete', $conversation->id) }}" method="POST" class="inline delete-form">
                                            @csrf
                                            <button type="submit" class="btn-delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty">Aucune conversation privée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $conversations->links() }}
                </div>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert2 pour les suppressions
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Supprimer cette conversation ?',
                    text: 'Tous les messages seront supprimés.',
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

        // Recherche
        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const participants = row.dataset.participants || '';
                const id = row.dataset.id || '';
                const matchSearch = participants.includes(searchValue) || id.includes(searchValue);
                row.style.display = matchSearch ? '' : 'none';
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

                    if (['id', 'messages', 'date'].includes(sortKey)) {
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
