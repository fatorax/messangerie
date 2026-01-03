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
                'resources/scss/pages/index.scss',
                'resources/scss/pages/login.scss',
                'resources/scss/pages/register.scss',
                'resources/scss/pages/dashboard/dashboard.scss',
                'resources/scss/pages/dashboard/modal/app.scss',
                'resources/scss/admin.scss',
                'resources/js/dashboard/CreateChannelModal.js',
                'resources/js/dashboard/EditChannelModal.js',
                'resources/js/dashboard/DeleteChannelModal.js',
                'resources/js/dashboard/searchUserAddModal.js',
                'resources/js/dashboard/FriendRequestViewModal.js',
                'resources/js/dashboard/DeleteMessageModal.js',
                'resources/js/dashboard/driverTour.js',
                'resources/js/auth/profilePicturePreview.js',
                'resources/js/dashboard/chatForm.js',
                'resources/js/app.js',
                'resources/js/checkPasswordStrength.js',
                'resources/js/viewPassword.js',
            ],
            refresh: true,
        }),
    ],
});
