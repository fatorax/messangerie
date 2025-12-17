<div class="searchUserAddModal modal">
    <div class="box">
        <header>
            <h2>Chercher un utilisateur</h2>
            <button type="button" onclick="closeSearchUserAddModal()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </header>
        <form onsubmit="event.preventDefault(); searchUserAddModal();">
            @csrf
            <div class="form-group">
                <label for="">Utilisateur</label>
                <input type="text" name="name" placeholder="Utilisateur" max="255" required>
                <span class="error error-name hidden"></span>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Créer</button>
        </form>
    </div>
</div>