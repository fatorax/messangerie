const spanPasswordLevel = document.querySelector('.password-level');
const passwordInput = document.querySelector('input[name="password"]');

// Fonction pour vérifier la force du mot de passe
function checkPasswordStrength(password) {
    let strength = 0;
    const feedback = {
        score: 0,
        level: 'Très faible',
        color: '#d32f2f'
    };

    // Vérifier la longueur
    if (password.length >= 8) strength++;

    // Vérifier la présence de majuscules
    if (/[A-Z]/.test(password)) strength++;

    // Vérifier la présence de minuscules
    if (/[a-z]/.test(password)) strength++;

    // Vérifier la présence de chiffres
    if (/[0-9]/.test(password)) strength++;

    // Vérifier la présence de caractères spéciaux
    if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) strength++;

    // Assigner le niveau de force
    if (strength <= 1) {
        feedback.level = 'Très faible';
        feedback.color = '#d32f2f';
        feedback.score = 1;
    } else if (strength <= 2) {
        feedback.level = 'Faible';
        feedback.color = '#f57c00';
        feedback.score = 2;
    } else if (strength <= 4) {
        feedback.level = 'Moyen';
        feedback.color = '#fbc02d';
        feedback.score = 3;
    } else if (strength <= 5 && password.length >= 15) {
        feedback.level = 'Très fort';
        feedback.color = '#2e7d32';
        feedback.score = 6;
    } else if (strength <= 5 && password.length >= 12) {
        feedback.level = 'Fort';
        feedback.color = '#388e3c';
        feedback.score = 5;
    } else {
        feedback.level = 'Bon';
        feedback.color = '#7cb342';
        feedback.score = 4;
    }

    return feedback;
}

passwordInput.addEventListener('input', function() {
    const password = this.value;
    
    if (!password) {
        spanPasswordLevel.innerHTML = '';
        spanPasswordLevel.classList.add('hidden');
        return;
    } else {
        spanPasswordLevel.classList.remove('hidden');
    }

    const passwordStrength = checkPasswordStrength(password);
    spanPasswordLevel.innerHTML = `<span style="color: ${passwordStrength.color}; font-weight: bold;">${passwordStrength.level}</span>`;
});
