const passwordInput = document.querySelector('input[name="password"]');
const viewPassword = document.querySelector('.view-password');
const hiddenPassword = document.querySelector('.hidden-password');

const passwordConfirmInput = document.querySelector('input[name="password-confirm"]');
const viewPasswordConfirm = document.querySelector('.view-password-confirm');
const hiddenPasswordConfirm = document.querySelector('.hidden-password-confirm');

viewPassword.addEventListener('click', function() {
    passwordInput.type = 'text';
    viewPassword.classList.add('hidden');
    hiddenPassword.classList.remove('hidden');
});

hiddenPassword.addEventListener('click', function() {
    passwordInput.type = 'password';
    viewPassword.classList.remove('hidden');
    hiddenPassword.classList.add('hidden');
});

viewPasswordConfirm.addEventListener('click', function() {
    passwordConfirmInput.type = 'text';
    viewPasswordConfirm.classList.add('hidden');
    hiddenPasswordConfirm.classList.remove('hidden');
});

hiddenPasswordConfirm.addEventListener('click', function() {
    passwordConfirmInput.type = 'password';
    viewPasswordConfirm.classList.remove('hidden');
    hiddenPasswordConfirm.classList.add('hidden');
});