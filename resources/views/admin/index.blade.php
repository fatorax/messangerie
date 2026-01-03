<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administration - Messangerie</title>
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
                <a href="{{ route('admin') }}" class="nav-item active">
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
                <h2>Dashboard</h2>
                <div class="user-info">
                    <span>{{ auth()->user()->username }}</span>
                    <img src="{{ asset('storage/users/' . auth()->user()->avatar) }}" alt="Avatar">
                </div>
            </header>

            <div class="content-body">
                {{-- Stats cards --}}
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon users">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['users'] }}</h3>
                            <p>Utilisateurs</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon channels">
                            <i class="fa-solid fa-hashtag"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['channels'] }}</h3>
                            <p>Channels publics</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon conversations">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['conversations'] }}</h3>
                            <p>Conversations privées</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon messages">
                            <i class="fa-solid fa-message"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $stats['messages'] }}</h3>
                            <p>Messages</p>
                        </div>
                    </div>
                </div>

                {{-- Recent activity --}}
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Derniers utilisateurs</h3>
                        <ul class="activity-list">
                            @forelse ($recentUsers as $user)
                                <li>
                                    <img src="{{ asset('storage/users/' . $user->avatar) }}" alt="Avatar">
                                    <div class="activity-info">
                                        <strong>{{ $user->username }}</strong>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                    <span class="activity-date">{{ $user->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="empty">Aucun utilisateur récent</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="activity-card">
                        <h3>Derniers messages</h3>
                        <ul class="activity-list">
                            @forelse ($recentMessages as $message)
                                <li>
                                    <img src="{{ asset('storage/users/' . ($message->user->avatar ?? 'default.webp')) }}" alt="Avatar">
                                    <div class="activity-info">
                                        <strong>{{ $message->user->username ?? 'Utilisateur supprimé' }}</strong>
                                        <span>{{ Str::limit($message->content, 40) }}</span>
                                    </div>
                                    <span class="activity-date">{{ $message->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="empty">Aucun message récent</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</body>
</html>
