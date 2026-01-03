<div class="editChannelModal modal">
    <div class="box">
        <header>
            <h2>Paramètres du channel</h2>
            <button type="button" onclick="closeEditChannelModal()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </header>
        <form onsubmit="event.preventDefault(); editChannel({{ $conversationView->id }});" {{ $conversationView->type != 'global' ? 'style=display:none;' : '' }} enctype="multipart/form-data">
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
            <div class="form-group">
                <label for="channel-image">Image du channel</label>
                <div class="image-upload">
                    <div class="image-preview" id="channel-image-preview">
                        @if($conversationView->image)
                            <img src="{{ asset('storage/channels/' . $conversationView->image) }}" alt="Image du channel">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        @endif
                    </div>
                    <div class="image-actions">
                        <input type="file" name="image" id="channel-image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewChannelImage(this)" {{ $conversationView->type != 'global' ? 'disabled' : '' }}>
                        <label for="channel-image" class="upload-btn">Choisir une image</label>
                        @if($conversationView->image)
                            <button type="button" class="delete-image-btn" onclick="deleteChannelImage({{ $conversationView->id }})">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                Supprimer
                            </button>
                        @endif
                    </div>
                </div>
                <span class="error error-image hidden"></span>
            </div>
            <button type="submit" {{ $conversationView->type != 'global' ? 'disabled' : '' }}>Modifier</button>
        </form>
        @if($conversationView->id != 1)
            <button class="delete" onclick="deleteChannel({{ $conversationView->id }})">Supprimer</button>
        @endif
    </div>
</div>