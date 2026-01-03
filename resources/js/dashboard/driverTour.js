import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';

// Clé localStorage pour savoir si le tour a déjà été fait
const TOUR_COMPLETED_KEY_USER = 'messangerie_tour_completed_user';
const TOUR_COMPLETED_KEY_DEMO = 'messangerie_tour_completed_demo';

// Récupérer le rôle de l'utilisateur depuis la meta tag
function getUserRole() {
    const meta = document.querySelector('meta[name="user-role"]');
    return meta ? meta.content : 'user';
}

// Configuration commune
const commonConfig = {
    showProgress: true,
    animate: true,
    overlayColor: 'rgba(0, 0, 0, 0.75)',
    stagePadding: 10,
    stageRadius: 8,
    allowClose: true,
    doneBtnText: 'Terminer',
    closeBtnText: 'Fermer',
    nextBtnText: 'Suivant',
    prevBtnText: 'Précédent',
    progressText: '{{current}} sur {{total}}',
};

// ============================================
// Tour pour les utilisateurs normaux
// ============================================
const userSteps = [
    {
        element: '.content',
        popover: {
            title: '👋 Bienvenue sur Messangerie !',
            description: 'Découvrons ensemble les fonctionnalités principales de l\'application.',
            side: 'top',
            align: 'center'
        }
    },
    {
        element: '.channels:first-child',
        popover: {
            title: '📺 Channels publics',
            description: 'Ici se trouvent tous les channels publics. Cliquez sur un channel pour rejoindre la conversation.',
            side: 'right',
            align: 'start'
        }
    },
    {
        element: '.channels:last-child',
        popover: {
            title: '💬 Messages privés',
            description: 'Vos conversations privées avec vos amis apparaissent ici.',
            side: 'right',
            align: 'start'
        }
    },
    {
        element: '.channels:last-child .head button',
        popover: {
            title: '👥 Ajouter un ami',
            description: 'Recherchez un utilisateur par son pseudonyme pour lui envoyer une demande d\'ami.',
            side: 'right',
            align: 'center'
        }
    },
    {
        element: '.friendRequest',
        popover: {
            title: '📩 Demandes d\'ami',
            description: 'Consultez et gérez vos demandes d\'ami reçues et envoyées.',
            side: 'bottom',
            align: 'center'
        }
    },
    {
        element: '.header',
        popover: {
            title: '📍 En-tête de conversation',
            description: 'Affiche le nom du channel actuel et les options disponibles (suppression si c\'est un channel privé).',
            side: 'bottom',
            align: 'center'
        }
    },
    {
        element: '.chat-box',
        popover: {
            title: '💭 Zone de messages',
            description: 'Tous les messages de la conversation s\'affichent ici en temps réel grâce à WebSocket.',
            side: 'top',
            align: 'center'
        }
    },
    {
        element: '.footer-channel',
        popover: {
            title: '✍️ Envoyer un message',
            description: 'Tapez votre message ici et appuyez sur Entrée ou cliquez sur le bouton pour l\'envoyer.',
            side: 'top',
            align: 'center'
        }
    },
    {
        element: 'nav footer',
        popover: {
            title: '⚙️ Votre profil',
            description: 'Accédez à vos paramètres, modifiez votre profil ou déconnectez-vous.',
            side: 'top',
            align: 'center'
        }
    },
    {
        popover: {
            title: '🎉 C\'est parti !',
            description: 'Vous êtes prêt à utiliser Messangerie ! N\'hésitez pas à explorer toutes les fonctionnalités. Bonne discussion !',
        }
    }
];

// ============================================
// Tour pour les comptes de démonstration
// ============================================
const demoSteps = [
    {
        element: '.content',
        popover: {
            title: '👋 Bienvenue sur la démo !',
            description: 'Découvrez Messangerie avec ce compte de démonstration. Certaines fonctionnalités sont limitées.',
            side: 'top',
            align: 'center'
        }
    },
    {
        element: '.channels:first-child',
        popover: {
            title: '📺 Channels publics',
            description: '⚠️ En mode démo, vous n\'avez pas accès aux channels publics. Créez un compte pour y accéder !',
            side: 'right',
            align: 'start'
        }
    },
    {
        element: '.channels:last-child',
        popover: {
            title: '💬 Conversations de test',
            description: 'En mode démo, vous pouvez discuter uniquement avec d\'autres utilisateurs de test pour essayer l\'application.',
            side: 'right',
            align: 'start'
        }
    },
    {
        element: '.title-channel',
        popover: {
            title: '📍 En-tête de conversation',
            description: 'Affiche le nom de la conversation actuelle.',
            side: 'bottom',
            align: 'center'
        }
    },
    {
        element: '.chat-box',
        popover: {
            title: '💭 Zone de messages',
            description: 'Les messages s\'affichent ici en temps réel.',
            side: 'top',
            align: 'center'
        }
    },
    {
        element: '.footer-channel',
        popover: {
            title: '✍️ Envoyer un message',
            description: 'Tapez votre message et envoyez-le pour tester la messagerie en temps réel.',
            side: 'top',
            align: 'center'
        }
    },
    {
        element: 'nav footer',
        popover: {
            title: '🚪 Déconnexion',
            description: 'Votre compte de démonstration sera automatiquement supprimé après 24h.',
            side: 'top',
            align: 'center'
        }
    },
    {
        popover: {
            title: '🎉 Bonne découverte !',
            description: 'Explorez l\'application ! Pour profiter de toutes les fonctionnalités, créez un compte gratuit.',
        }
    }
];

// Créer le driver selon le rôle
function createDriver(role) {
    const steps = role === 'demo' ? demoSteps : userSteps;
    const storageKey = role === 'demo' ? TOUR_COMPLETED_KEY_DEMO : TOUR_COMPLETED_KEY_USER;
    
    return driver({
        ...commonConfig,
        onDestroyStarted: () => {
            localStorage.setItem(storageKey, 'true');
            driverInstance.destroy();
        },
        steps: steps
    });
}

let driverInstance = null;

// Fonction pour démarrer le tour
export function startTour() {
    const role = getUserRole();
    driverInstance = createDriver(role);
    driverInstance.drive();
}

// Fonction pour réinitialiser le tour (permet de le refaire)
export function resetTour() {
    const role = getUserRole();
    const storageKey = role === 'demo' ? TOUR_COMPLETED_KEY_DEMO : TOUR_COMPLETED_KEY_USER;
    localStorage.removeItem(storageKey);
}

// Fonction pour vérifier si c'est la première visite
export function isFirstVisit() {
    const role = getUserRole();
    const storageKey = role === 'demo' ? TOUR_COMPLETED_KEY_DEMO : TOUR_COMPLETED_KEY_USER;
    return !localStorage.getItem(storageKey);
}

// Démarrer automatiquement le tour si c'est la première visite
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        if (isFirstVisit()) {
            startTour();
        }
    }, 500);
});

// Exposer les fonctions globalement
window.MessangerieTour = {
    start: startTour,
    reset: resetTour,
    isFirstVisit: isFirstVisit
};
