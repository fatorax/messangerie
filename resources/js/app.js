import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Ensure Echo sends auth requests via XHR (axios) and includes CSRF header.
// axios.defaults.withCredentials is set in bootstrap.js so cookies will be sent.
if (window.Echo) {
    try {
        const tokenMeta = document.head.querySelector('meta[name="csrf-token"]');
        const csrf = tokenMeta ? tokenMeta.content : null;
        // Reconfigure auth settings to guarantee proper headers
        window.Echo.options.auth = window.Echo.options.auth || {};
        window.Echo.options.auth.headers = Object.assign(window.Echo.options.auth.headers || {}, csrf ? {'X-CSRF-TOKEN': csrf} : {});
        // Use ajax transport for auth (uses axios)
        window.Echo.options.authTransport = window.Echo.options.authTransport || 'ajax';
        // Temporary: send auth requests to debug route that logs request details
        window.Echo.options.auth = window.Echo.options.auth || {};
        window.Echo.options.auth.endpoint = window.Echo.options.auth.endpoint || '/debug-broadcasting/auth';
    } catch (e) {
        console.warn('Echo auth config patch failed', e);
    }
}

