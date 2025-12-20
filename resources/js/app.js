import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});



// Récupère l'ID de la conversation dynamiquement depuis une balise meta ou un attribut data
let conversationId = 1;
const metaConv = document.querySelector('meta[name="conversation-id"]');
if (metaConv) {
    conversationId = metaConv.content;
} else {
    const el = document.getElementById('conversation-root');
    if (el && el.dataset.conversationId) {
        conversationId = el.dataset.conversationId;
    }
}

console.log('Écoute des messages pour la conversation ID : ', conversationId);

window.Echo.private(`chat.${conversationId}`)
    .listen('.MessageSent', (e) => {
        console.log('Message reçu :', e);
        // Affichage du message dans le chat si la structure du payload correspond
        const chatBox = document.getElementById('chat-box');
        if (!chatBox) return;
        // Si le payload contient directement le message (ex: e.content)
        const content = e.content || (e.message && e.message.content) || '';
        const username = e.user?.username || e.user_username || (e.message && (e.message.user?.username || e.message.user_username)) || 'Utilisateur';
        const createdAt = e.created_at || (e.message && e.message.created_at) || '';
        // Formatage date simple
        function formatDate(input) {
            if (!input) return '';
            let s = String(input).trim();
            if (/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:?\d{0,2}$/.test(s)) s = s.replace(' ', 'T');
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return '';
            const pad = n => String(n).padStart(2, '0');
            return `${pad(d.getDate())}/${pad(d.getMonth()+1)}/${pad(d.getFullYear())} ${pad(d.getHours())}:${pad(d.getMinutes())}`;
        }
        const wrapper = document.createElement('div');
        wrapper.classList.add('chat-box-message');
        // Récupère l'id utilisateur courant depuis une balise meta
        const metaUser = document.querySelector('meta[name="current-user-id"]');
        const currentUserId = metaUser ? metaUser.content : null;
        // Cherche l'id de l'expéditeur dans le payload
        const senderId = e.user_id || (e.user && e.user.id) || (e.message && (e.message.user_id || (e.message.user && e.message.user.id)));
        if (currentUserId && senderId && String(senderId) === String(currentUserId)) {
            wrapper.classList.add('active');
        }
        // Pas de $user->id ici, donc pas de coloration "active" côté JS
        const picDiv = document.createElement('div');
        picDiv.className = 'chat-box-picture';
        const img = document.createElement('img');
        img.src = 'https://picsum.photos/seed/picsum/200/300';
        img.alt = 'Image de profil';
        picDiv.appendChild(img);
        const infoDiv = document.createElement('div');
        infoDiv.className = 'chat-box-informations';
        const nameDiv = document.createElement('div');
        nameDiv.className = 'chat-box-informations-name';
        const h2 = document.createElement('h2');
        h2.textContent = username;
        const pDate = document.createElement('p');
        pDate.className = 'date';
        pDate.textContent = formatDate(createdAt);
        nameDiv.appendChild(h2);
        nameDiv.appendChild(pDate);
        const msgContainer = document.createElement('div');
        msgContainer.className = 'chat-box-informations-message';
        const p = document.createElement('p');
        p.textContent = content;
        msgContainer.appendChild(p);
        infoDiv.appendChild(nameDiv);
        infoDiv.appendChild(msgContainer);
        wrapper.appendChild(picDiv);
        wrapper.appendChild(infoDiv);
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    });

// Gestion dynamique du rond de connexion dans la nav
function setOnlineStatus(userId, online) {
    const pic = document.querySelector('.picture[data-user-id="' + userId + '"]');
    if (!pic) return;
    let dot = pic.querySelector('.connected');
    if (online) {
        if (!dot) {
            dot = document.createElement('div');
            dot.className = 'connected online';
            dot.setAttribute('data-user-id', userId);
            pic.appendChild(dot);
        } else {
            dot.classList.add('online');
        }
    } else {
        if (dot) dot.remove();
    }
}

if (window.Echo) {
    window.Echo.join('online')
        .here((users) => {
            console.log('Utilisateurs en ligne :', users);
            document.querySelectorAll('.picture[data-user-id]').forEach(pic => {
                const userId = pic.getAttribute('data-user-id');
                setOnlineStatus(userId, users.some(u => String(u.id) === String(userId)));
            });
        })
        .joining((user) => {
            console.log('Utilisateur en ligne :', user);
            setOnlineStatus(user.id, true);
        })
        .leaving((user) => {
            console.log('Utilisateur hors ligne :', user);
            setOnlineStatus(user.id, false);
        });
}

window.Echo.private(`conversation.deleted.${conversationId}`)
    .listen('.conversation.deleted', () => {
        const link = document.querySelector(`[href$='/channels/${conversationId}']`);
        if (link) {
            link.remove();
        }
        window.location.href = '/channels';
    });