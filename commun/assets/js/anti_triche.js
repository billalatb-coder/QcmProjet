document.addEventListener("DOMContentLoaded", () => {
    // Éléments UI initiaux
    const startOverlay = document.getElementById('start-overlay');
    const qcmContainer = document.getElementById('qcm-container');
    const btnStartFullscreen = document.getElementById('btn-start-fullscreen');
    
    // Initialisation du Timer
    let timeRemaining = typeof timeRemainingInitial !== 'undefined' ? timeRemainingInitial : 600;
    const timeDisplay = document.getElementById('time-display');
    const qcmForm = document.getElementById('qcm-form');
    
    let timerInterval;
    let isSubmitting = false;
    let qcmStarted = false;
    let fullscreenEscapes = 0;

    let wasInFullscreen = false;

   
    let fullscreenTransitionInProgress = false;
   // Fonction pour démarrer le timer
    function startTimer() {
        if (timeDisplay && !timerInterval) { 
            timerInterval = setInterval(() => {
                timeRemaining--;
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval); 
                    if (isSubmitting) return;
                    isSubmitting = true;
                    if (qcmForm) {
                        qcmForm.submit(); 
                    } else {
                        window.location.href = 'process.php';
                    }
                } else {
                    let m = Math.floor(timeRemaining / 60);
                    let s = timeRemaining % 60;
                    timeDisplay.textContent = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                }
            }, 1000);
        }
    }
   
    // Démarrage du QCM si l'utilisateur a déjà cliqué sur "Commencer"
    if (!startOverlay) {
        startTimer();
    }   



    // Gestion de l'Anti-triche
    const overlay = document.getElementById('cheat-overlay');
    const resumeBtn = document.getElementById('resume-btn');

    function showOverlay() {
        if (overlay && qcmStarted) { 
            overlay.style.display = 'flex';
        }
    }

    function hideOverlay() {
        if (overlay) overlay.style.display = 'none';
        enterFullscreen();
    }

    function enterFullscreen() {
        // On signale qu'une transition est en cours
        fullscreenTransitionInProgress = true;
        
        let elem = document.documentElement;

        
        if (elem.requestFullscreen) {
            elem.requestFullscreen()
                .then(() => {
                    fullscreenTransitionInProgress = false;
                })
                .catch((err) => {
                    console.error("Échec du plein écran :", err);
                    fullscreenTransitionInProgress = false;
                });
        }
    }

    // Lancement explicite du QCM
    if (btnStartFullscreen) {
        btnStartFullscreen.addEventListener('click', () => {
            enterFullscreen();
            startOverlay.style.display = 'none';
            if (qcmContainer) qcmContainer.style.display = 'block';
            qcmStarted = true;
            wasInFullscreen = true; // On est maintenant en plein écran
            startTimer();
        });
    }

    if (resumeBtn) {
        resumeBtn.addEventListener('click', hideOverlay);
    }
    
    // Intercepter la soumission manuelle du formulaire
    if (qcmForm) {
        qcmForm.addEventListener('submit', () => {
            isSubmitting = true;
        });
    }

    // 1. Désactiver le clic droit
    document.addEventListener('contextmenu', event => {
        event.preventDefault();
    });

    // 2. Désactiver le copier/coller
    document.addEventListener('copy', event => {
        event.preventDefault();
        alert("La copie de texte est interdite pendant l'examen.");
    });
    
    // 3. Désactiver la sélection de texte
    document.addEventListener('selectstart', event => {
        if (event.target.tagName !== 'INPUT') {
            event.preventDefault();
        }
    });

    // 4. Détection du changement d'onglet ou minimisation (Triche directe -> 0)
    // On ignore ce déclenchement si on est en plein milieu d'une transition plein écran
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden' && qcmStarted && !isSubmitting) {
            if (!fullscreenTransitionInProgress) {
                isSubmitting = true;
                window.location.href = 'process.php?cheat=1';
            }
        }
    });

    
    function handleFullscreenChange() {

    let isNowInFullscreen = false;
    if (document.fullscreenElement) {
        isNowInFullscreen = true;
    }

    
    if (wasInFullscreen === true && isNowInFullscreen === false) {
        
        
        if (qcmStarted === true) {
            fullscreenEscapes = fullscreenEscapes + 1; 

            if (fullscreenEscapes === 1) {
                showOverlay(); 
            } else {
                isSubmitting = true;
                window.location.href = 'process.php?cheat=1'; 
            }
        }
    }

    // 3. On enregistre l'état actuel pour la prochaine vérification
    wasInFullscreen = isNowInFullscreen;
}

    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange); // Safari/IE fallbacks
});
