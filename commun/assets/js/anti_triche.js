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

    function startTimer() {
        if (timeDisplay && !timerInterval) {
            timerInterval = setInterval(() => {
                timeRemaining--;
                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
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
    let qcmStarted = false;
    let fullscreenEscapes = 0;

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
        let elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(err => console.log(err));
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    }

    // Lancement explicite du QCM
    if (btnStartFullscreen) {
        btnStartFullscreen.addEventListener('click', () => {
            enterFullscreen();
            startOverlay.style.display = 'none';
            if (qcmContainer) qcmContainer.style.display = 'block';
            qcmStarted = true;
            startTimer();
        });
    }

    if (resumeBtn) {
        resumeBtn.addEventListener('click', hideOverlay);
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

    // 4. Détection du changement d'onglet ou minimisation de la fenêtre (Triche directe -> 0)
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden' && qcmStarted) {
            window.location.href = 'process.php?cheat=1';
        }
    });

    // 5. Détection si l'utilisateur quitte le plein écran (Touche Echap)
    function handleFullscreenChange() {
        if (!document.fullscreenElement && !document.webkitFullscreenElement && qcmStarted) {
            fullscreenEscapes++;
            if (fullscreenEscapes === 1) {
                // Premier avertissement
                showOverlay();
            } else {
                // Deuxième fois -> Triche directe -> 0
                window.location.href = 'process.php?cheat=1';
            }
        }
    }

    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange); // Safari/IE fallbacks
});
