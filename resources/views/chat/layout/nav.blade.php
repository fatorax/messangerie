<nav>
    <header>
        <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
        @if(Auth::user()->role !== 'demo')
            <div class="friendRequest">
                <span id="friendRequestCounter" class="{{ auth()->user()->receivedFriendRequests()->count() == 0 ? 'hidden' : '' }}">{{ auth()->user()->receivedFriendRequests()->count() }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" onclick="openFriendRequestViewModal()" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
        @endif
    </header>
    <main>
        <div class="channels">
            <div class="head">
                <h2>Channels</h2>
                @if(Auth::user()->role == 'admin')
                    <button type="button" onclick="openCreateChannelModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                @endif
            </div>
            <div class="links" id="channelsPublicList">
                @if(auth()->user()->role !== 'demo')
                    @if($channelsPublic->count() == 0)
                        <p>Aucun channel</p>
                    @else
                        @foreach($channelsPublic as $channel)
                            @php
                                $unreadCount = $unreadCounts[$channel->id] ?? 0;
                            @endphp
                            <a href="{{ route('channel.view', $channel->id) }}" @class(['link', 'active' => $channel->id == $conversationView->id]) data-conversation-id="{{ $channel->id }}">
                                <div class="picture">
                                    <img src="{{ $channel->image ? asset('storage/channels/' . $channel->image) : asset('storage/users/default.webp') }}" alt="Image du channel">
                                </div>
                                <p>{{ $channel->name }}</p>
                                <span class="message-counter {{ $unreadCount == 0 ? 'hidden' : '' }}">{{ $unreadCount }}</span>
                            </a>
                        @endforeach
                    @endif
                @else
                    <p>Les comptes de test ne peuvent pas accéder aux channels publics.</p>
                @endif
            </div>
        </div>
        <div class="channels">
            <div class="head">
                <h2>Amis</h2>
                @if(auth()->user()->role !== 'demo')
                    <button onclick="openSearchUserAddModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                @endif
            </div>
            <div class="links" id="channelsPrivateList">
                @foreach($channelsPrivate as $channel)
                    @php
                        $otherUser = $channel->users->first();
                        $unreadCount = $unreadCounts[$channel->id] ?? 0;
                    @endphp
                    <a href="{{ route('channel.view', $channel->id) }}" @class(['link', 'active' => $channel->id == $conversationView->id]) data-conversation-id="{{ $channel->id }}">
                        <div class="picture" data-user-id="{{ $otherUser->id }}">
                            <img src="{{ asset('storage/users/' . $otherUser->avatar) }}" alt="Image de profil">
                        </div>
                        <p>{{ $otherUser->username }}</p>
                        <span class="message-counter {{ $unreadCount == 0 ? 'hidden' : '' }}">{{ $unreadCount }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
    <footer>
        <div class="picture">
            <img src="{{ asset('storage/users/' . $user->avatar) }}" alt="Image de profil">
            <div class="connected"></div>
        </div>
        <div class="name">
            <h2>{{ $user->username }}</h2>
            <p>En ligne</p>
        </div>
        <div class="logout">
            @if(auth()->user()->role == 'admin')
                <a href="{{ route("admin") }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-icon lucide-shield"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                </a>
            @endif
            @if(auth()->user()->role !== 'demo')
                <a href="{{ route("settings") }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings"><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
            @endif
            <form action="{{ route("logout") }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                </button>
            </form>
        </div>
    </footer>
</nav>