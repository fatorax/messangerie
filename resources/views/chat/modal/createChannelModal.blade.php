<div class="createChannelModal modal">
    <div class="box">
        <header>
            <h2>Créer un nouveau canal</h2>
            <button type="button" onclick="closeCreateChannelModal()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </header>
        <form onsubmit="event.preventDefault(); createChannel();">
            @csrf
            <div class="form-group">
                <label for="">Nom</label>
                <input type="text" name="name" placeholder="Nom" max="255" required>
                <span class="error error-name hidden"></span>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Créer</button>
        </form>
    </div>
</div>