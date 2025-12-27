<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://js.pusher.com/8.0/pusher.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://js.pusher.com/8.4/pusher.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="conversation-id" content="{{ $conversationView->id }}">
    <meta name="current-user-id" content="{{ $user->id }}">
    @vite([
        'resources/scss/pages/dashboard/dashboard.scss',
        'resources/scss/pages/dashboard/modal/app.scss',
        'resources/js/dashboard/CreateChannelModal.js',
        'resources/js/dashboard/EditChannelModal.js',
        'resources/js/dashboard/DeleteChannelModal.js',
        'resources/js/dashboard/searchUserAddModal.js',
        'resources/js/dashboard/FriendRequestViewModal.js',
        'resources/js/dashboard/DeleteMessageModal.js',
        'resources/js/dashboard/chatForm.js',
        'resources/js/app.js',
    ])
</head>
<body>
    @include('chat.modal.createChannelModal')
    @include('chat.modal.editChannelModal')
    @include('chat.modal.searchUserAddModal')
    @include('chat.modal.friendRequestViewModal')
    @include('chat.layout.nav')
    <div class="container">
        @include('chat.layout.header')
        @include('chat.layout.content')
        @include('chat.layout.footer')
    </div>
</body>
</html>