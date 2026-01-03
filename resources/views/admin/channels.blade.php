<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Channels - Administration</title>
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
                <a href="{{ route('admin.channels') }}" class="nav-item active">
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
                <h2>Gestion des channels</h2>
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
                </div>

                <div class="table-container">
                    <table class="admin-table" id="dataTable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th class="sortable" data-sort="id">ID <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="name">Nom <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="messages">Messages <i class="fa-solid fa-sort"></i></th>
                                <th class="sortable" data-sort="date">Créé le <i class="fa-solid fa-sort"></i></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($channels as $channel)
                                <tr data-id="{{ $channel->id }}" data-name="{{ strtolower($channel->name) }}" data-messages="{{ $channel->messages->count() }}" data-date="{{ $channel->created_at->timestamp }}">
                                    <td>
                                        @if ($channel->image)
                                            <img src="{{ asset('storage/channels/' . $channel->image) }}" alt="Channel" class="table-avatar">
                                        @else
                                            <div class="table-avatar placeholder">
                                                <i class="fa-solid fa-hashtag"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $channel->id }}</td>
                                    <td>{{ $channel->name }}</td>
                                    <td>{{ $channel->messages->count() }}</td>
                                    <td>{{ $channel->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin.conversation.messages', $channel->id) }}" class="btn-view" title="Voir les messages">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <button class="btn-edit" onclick="openEditModal({{ $channel->id }}, '{{ $channel->name }}', '{{ $channel->image }}')">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        @if ($channel->id !== 1)
                                            <form action="{{ route('admin.channels.delete', $channel->id) }}" method="POST" class="inline delete-form">
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
                                    <td colspan="6" class="empty">Aucun channel</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $channels->links() }}
                </div>
            </div>
        </main>
    </div>

    {{-- Modal édition channel --}}
    <div id="editChannelModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Modifier le channel</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editChannelForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="edit_name">Nom du channel</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit_image">Image du channel</label>
                    <div class="image-preview-container">
                        <img id="channel_image_preview" src="" alt="Preview" style="display: none; width: 80px; height: 80px; border-radius: 8px; object-fit: cover; margin-bottom: 10px;">
                        <button type="button" id="delete_image_btn" class="btn-delete-image" style="display: none;" onclick="deleteChannelImage()">
                            <i class="fa-solid fa-trash"></i> Supprimer l'image
                        </button>
                    </div>
                    <input type="file" id="edit_image" name="image" accept="image/*">
                    <input type="hidden" id="delete_image" name="delete_image" value="0">
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
        let currentChannelHasImage = false;

        function openEditModal(id, name, image) {
            document.getElementById('editChannelForm').action = `/admin/channels/${id}/update`;
            document.getElementById('edit_name').value = name;
            document.getElementById('delete_image').value = '0';
            
            const preview = document.getElementById('channel_image_preview');
            const deleteBtn = document.getElementById('delete_image_btn');
            
            if (image) {
                preview.src = `/storage/channels/${image}`;
                preview.style.display = 'block';
                deleteBtn.style.display = 'inline-flex';
                currentChannelHasImage = true;
            } else {
                preview.style.display = 'none';
                deleteBtn.style.display = 'none';
                currentChannelHasImage = false;
            }
            
            document.getElementById('editChannelModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editChannelModal').classList.remove('active');
        }

        function deleteChannelImage() {
            const preview = document.getElementById('channel_image_preview');
            const deleteBtn = document.getElementById('delete_image_btn');
            
            preview.style.display = 'none';
            deleteBtn.style.display = 'none';
            document.getElementById('delete_image').value = '1';
            document.getElementById('edit_image').value = '';
        }

        // Preview image on file select
        document.getElementById('edit_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('channel_image_preview');
            const deleteBtn = document.getElementById('delete_image_btn');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    deleteBtn.style.display = 'inline-flex';
                };
                reader.readAsDataURL(file);
                document.getElementById('delete_image').value = '0';
            }
        });

        document.getElementById('editChannelModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });

        // SweetAlert2 pour les suppressions
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Supprimer ce channel ?',
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
                const name = row.dataset.name || '';
                const id = row.dataset.id || '';
                const matchSearch = name.includes(searchValue) || id.includes(searchValue);
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
