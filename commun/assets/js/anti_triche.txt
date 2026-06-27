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

    // CORRECTION CLÉ : on mémorise si on était en plein écran
    // Cela évite le double déclenchement de fullscreenchange + webkitfullscreenchange
    // qui comptait deux fois la même sortie du plein écran
    let wasInFullscreen = false;

    // Flag pour éviter que visibilitychange réagisse pendant les transitions
    // d'entrée/sortie du plein écran (le navigateur peut cacher la page brièvement)
    let fullscreenTransitionInProgress = false;

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

    // Si on n'a pas l'overlay de départ (ex: sur process.php ou error), on démarre direct
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
        // On signale qu'une transition est en cours pour ignorer visibilitychange pendant ce temps
        fullscreenTransitionInProgress = true;
        let elem = document.documentElement;
        let promise;
        if (elem.requestFullscreen) {
            promise = elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            promise = elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            promise = elem.msRequestFullscreen();
        }
        // On attend la confirmation du navigateur avant de lever le flag
        if (promise && typeof promise.then === 'function') {
            promise.then(() => {
                fullscreenTransitionInProgress = false;
            }).catch(() => {
                fullscreenTransitionInProgress = false;
            });
        } else {
            // Fallback si le navigateur ne retourne pas de Promise
            setTimeout(() => { fullscreenTransitionInProgress = false; }, 500);
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

    // 5. Détection si l'utilisateur quitte le plein écran (Touche Echap)
    // CORRECTION : on utilise wasInFullscreen pour ne compter qu'UNE SEULE sortie,
    // même si les deux événements (fullscreenchange ET webkitfullscreenchange) se déclenchent
    // en même temps sur Chrome/Chromium (ce qui doublait le compteur fullscreenEscapes).
    function handleFullscreenChange() {
        const isNowInFullscreen = !!(document.fullscreenElement || document.webkitFullscreenElement);

        // On réagit SEULEMENT si on vient de sortir du plein écran (était dedans, maintenant dehors)
        if (!isNowInFullscreen && wasInFullscreen && qcmStarted) {
            fullscreenEscapes++;
            if (fullscreenEscapes === 1) {
                // Premier avertissement
                showOverlay();
            } else {
                // Deuxième fois -> Triche directe -> 0
                if (!isSubmitting) {
                    isSubmitting = true;
                    window.location.href = 'process.php?cheat=1';
                }
            }
        }

        // On mémorise l'état actuel pour la prochaine comparaison
        wasInFullscreen = isNowInFullscreen;
    }

    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange); // Safari/IE fallbacks
});
