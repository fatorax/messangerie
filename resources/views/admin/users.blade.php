<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Utilisateurs - Administration</title>
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
                <a href="{{ route('admin.users') }}" class="nav-item active">
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
                <h2>Gestion des utilisateurs</h2>
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
                        <select id="roleFilter" onchange="filterTable()">
                            <option value="">Tous les rôles</option>
                            <option value="user">Utilisateur</option>
                            <option value="admin">Admin</option>
                            <option value="demo">Démo</option>
                        </select>
                    </div>
                </div>

                <div class="table-container">
                    <table class="admin-table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th class="sortable" data-sort="username">Username <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="firstname">Prénom <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="lastname">Nom <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="email">Email <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="role">Rôle <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="date">Inscrit le <i class="fa-solid fa-sort"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr data-username="{{ strtolower($user->username) }}" data-firstname="{{ strtolower($user->firstname) }}" data-lastname="{{ strtolower($user->lastname) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}" data-date="{{ $user->created_at->timestamp }}">
                                    <td>
                                        <img src="{{ asset('storage/users/' . $user->avatar) }}" alt="Avatar" class="table-avatar">
                                    </td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->firstname }}</td>
                                    <td>{{ $user->lastname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role }}">{{ $user->role }}</span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="actions">
                                        @if ($user->id !== auth()->id())
                                            <button class="btn-edit" onclick="openEditModal({{ $user->id }}, '{{ $user->username }}', '{{ $user->firstname }}', '{{ $user->lastname }}', '{{ $user->email }}', '{{ $user->role }}')">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline delete-form">
                                                @csrf
                                                <button type="submit" class="btn-delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="empty">Aucun utilisateur</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $users->links() }}
                </div>
            </div>
        </main>
    </div>

    {{-- Modal édition utilisateur --}}
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Modifier l'utilisateur</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="edit_username">Username</label>
                    <input type="text" id="edit_username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="edit_firstname">Prénom</label>
                    <input type="text" id="edit_firstname" name="firstname" required>
                </div>
                <div class="form-group">
                    <label for="edit_lastname">Nom</label>
                    <input type="text" id="edit_lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="edit_role">Rôle</label>
                    <select id="edit_role" name="role" required>
                        <option value="user">Utilisateur</option>
                        <option value="admin">Administrateur</option>
                        <option value="demo">Démo</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Annuler</button>
                    <button type="submit" class="btn-save">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openEditModal(id, username, firstname, lastname, email, role) {
            document.getElementById('editUserForm').action = `/admin/users/${id}/update`;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_firstname').value = firstname;
            document.getElementById('edit_lastname').value = lastname;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editUserModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.remove('active');
        }

        // Fermer modal si clic extérieur
        document.getElementById('editUserModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // SweetAlert2 pour les suppressions
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Supprimer cet utilisateur ?',
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
            const roleValue = document.getElementById('roleFilter').value;
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const username = row.dataset.username || '';
                const firstname = row.dataset.firstname || '';
                const lastname = row.dataset.lastname || '';
                const email = row.dataset.email || '';
                const role = row.dataset.role || '';

                const matchSearch = username.includes(searchValue) || 
                                   firstname.includes(searchValue) || 
                                   lastname.includes(searchValue) || 
                                   email.includes(searchValue);
                const matchRole = !roleValue || role === roleValue;

                row.style.display = (matchSearch && matchRole) ? '' : 'none';
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

                    if (sortKey === 'date') {
                        aVal = parseInt(aVal);
                        bVal = parseInt(bVal);
                    }

                    if (aVal < bVal) return sortDirection[sortKey] ? -1 : 1;
                    if (aVal > bVal) return sortDirection[sortKey] ? 1 : -1;
                    return 0;
                });

                rows.forEach(row => tbody.appendChild(row));

                // Update icons
                document.querySelectorAll('.sortable i').forEach(i => i.className = 'fa-solid fa-sort');
                this.querySelector('i').className = sortDirection[sortKey] ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down';
            });
        });
    </script>
</body>
</html>
