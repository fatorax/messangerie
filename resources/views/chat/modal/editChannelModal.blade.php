<div class="editChannelModal modal">
    <div class="box">
        <header>
            <h2>Paramètres du channel</h2>
            <button type="button" onclick="closeEditChannelModal()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </header>
        <form onsubmit="event.preventDefault(); editChannel({{ $conversationView->id }});" {{ $conversationView->type != 'global' ? 'style=display:none;' : '' }}>
            @csrf
            <div class="form-group">
                <input type="text" name="id" value="{{ $conversationView->id }}" hidden>
                <label for="">Nom</label>
                <input type="text" name="name" placeholder="{{ $conversationView->name }}" value="{{ $conversationView->name }}" max="255" required {{ $conversationView->type != 'global' ? 'disabled' : '' }}>
                <span class="error error-name hidden"></span>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" {{ $conversationView->type != 'global' ? 'disabled' : '' }}>Modifier</button>
        </form>
        @if($conversationView->id != 1)
            <button class="delete" onclick="deleteChannel({{ $conversationView->id }})">Supprimer</button>
        @endif
    </div>
</div>