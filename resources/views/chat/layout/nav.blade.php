<nav>
    <header>
        <a href="{{ route('dashboard') }}">{{ config('app.name') }}</a>
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
                            <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                        </div>
                        <p>{{ $channel->name }}</p>
                        @if(auth()->user()->role == 'admin' && $conversationView->id == $channel->id)
                            <div class="param">
                                <button onclick="openeditChannelModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                </button>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        <div class="channels">
            <div class="head">
                <h2>Messages privés</h2>
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
                        <div class="picture">
                            <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                            {{-- <div class="connected online"></div> --}}
                        </div>
                        <p>{{ $otherUser->username }}</p>
                        @if(auth()->user()->role == 'admin' && $conversationView->id == $channel->id)
                            <div class="param">
                                <button onclick="openeditChannelModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                </button>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </main>
    <footer>
        <div class="picture">
            <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
            <div class="connected"></div>
        </div>
        <div class="name">
            <h2>{{ $user->username }}</h2>
            <p>En ligne</p>
        </div>
        <div class="logout">
            <a href="{{ route("logout") }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out-icon lucide-log-out"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
            </a>
        </div>
    </footer>
</nav>