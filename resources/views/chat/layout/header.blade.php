<header class="header">
    <div class="left title-channel">
        <div class="hashtag">
            <svg xmlns="http://www.w3.org/2000/svg" class="hashtag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-hash-icon lucide-hash"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
        </div>
        <div class="title">
            <p class="title">
                @if($conversationView->type == 'global')
                    {{ $conversationView->name }}
                @else
                    @if($conversationView->users->first()->username == $user->username)
                        {{ $conversationView->users->last()->username }}
                    @else
                        {{ $conversationView->users->first()->username }}
                    @endif
                @endif
            </p>
            @if($conversationView->type == 'global')
                <div class="members">
                    <svg xmlns="http://www.w3.org/2000/svg" class="user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <p class="members">{{ $totalMembers }} membre(s)</p>
                </div>
            @endif
        </div>
    </div>
    <div class="right">
        @if(auth()->user()->role == 'admin' || ($conversationView->type == 'private' && $conversationView->users->contains(auth()->user()->id) && auth()->user()->role != 'demo'))
            <button onclick="openeditChannelModal()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-ellipsis-vertical-icon lucide-ellipsis-vertical"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
            </button>
        @elseif(auth()->user()->role == 'demo')
            <span class="demo">Mode démo</span>
        @endif
    </div>
</header>