<main>
    <div class="chat-box" id="chat-box">
        @foreach($messages as $message)
            <div @class(['chat-box-message', 'active' => $message->user_id == $user->id])>
                <div class="chat-box-picture">
                    <img src="https://picsum.photos/seed/picsum/200/300" alt="Image de profil">
                </div>
                <div class="chat-box-informations">
                    <div class="chat-box-informations-name">
                        <h2>{{ $user->username }}</h2>
                        <p class="date">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="chat-box-informations-message">
                        <p>{{ $message->content }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</main>