import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'messangerie.local', // ton domaine local
        port: 5173,
        hmr: {
            host: 'messangerie.local', // HMR doit utiliser le même domaine
            protocol: 'ws',
            port: 5173,
        },
        cors: true, // important !
    },
    plugins: [
        laravel({
            input: [
                'resources/scss/pages/login.scss',
                'resources/scss/pages/dashboard/dashboard.scss',
                'resources/scss/pages/dashboard/modal/app.scss',
                'resources/js/dashboard/CreateChannelModal.js',
                'resources/js/dashboard/EditChannelModal.js',
                'resources/js/dashboard/DeleteChannelModal.js',
                'resources/js/dashboard/searchUserAddModal.js',
                'resources/js/dashboard/FriendRequestViewModal.js',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
