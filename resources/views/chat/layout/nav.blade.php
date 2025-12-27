<nav>
    <header>
        <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
        <div class="friendRequest">
            <span id="friendRequestCounter" class="{{ auth()->user()->receivedFriendRequests()->count() == 0 ? 'hidden' : '' }}">{{ auth()->user()->receivedFriendRequests()->count() }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" onclick="openFriendRequestViewModal()" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
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
                @foreach($channelsPublic as $channel)
                    <a href="{{ route('channels.view', $channel->id) }}" @class(['link', 'active' => $channel->id == $conversationView->id])>
                        <div class="picture">
                            <img src="{{ asset('storage/users/default.webp') }}" alt="Image de profil">
                        </div>
                        <p>{{ $channel->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="channels">
            <div class="head">
                <h2>Amis</h2>
                <button onclick="openSearchUserAddModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                </button>
            </div>
            <div class="links" id="channelsPrivateList">
                @foreach($channelsPrivate as $channel)
                    @php
                        $otherUser = $channel->users->first();
                    @endphp
                    <a href="{{ route('channels.view', $channel->id) }}" @class(['link', 'active' => $channel->id == $conversationView->id])>
                        <div class="picture" data-user-id="{{ $otherUser->id }}">
                            <img src="{{ asset('storage/users/' . $otherUser->avatar) }}" alt="Image de profil">
                            <!-- Le rond de connexion sera géré dynamiquement par JS -->
                        </div>
                        <p>{{ $otherUser->username }}</p>
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
            <a href="{{ route("settings") }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings"><path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915"/><circle cx="12" cy="12" r="3"/></svg>
            </a>
            <form action="{{ route("logout") }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                </button>
            </form>
        </div>
    </footer>
</nav>