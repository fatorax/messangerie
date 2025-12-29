<main>
    <div class="chat-box" id="chat-box">
        @foreach($messages as $message)
            <div @class(['chat-box-message', 'active' => $message->user_id == $user->id]) data-message-id="{{ $message->id }}">
                <div class="chat-box-picture">
                    <img src="{{ asset('storage/users/' . $message->user->avatar) }}" alt="Image de profil">
                </div>
                <div class="chat-box-informations">
                    <div class="chat-box-informations-name">
                        <h2>{{ $message->user->username }}</h2>
                        <p class="date">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="chat-box-informations-message">
                        @if($message->user->id == $user->id)
                            <button onclick="deleteMessage(this)" class="delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-icon lucide-trash"><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        @endif
                        <p>{!! nl2br(e($message->content)) !!}</p>
                    </div>
                    @if($message->user_id == $user->id)
                        <div class="chat-box-informations-status" data-message-id="{{ $message->id }}">
                            @if($message->isRead())
                                <span class="read-status read" title="Lu">Lu</span>
                            @else
                                <span class="read-status sent" title="Envoyé">Envoyé</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</main>