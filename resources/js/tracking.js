/**
 * Gère la capture des données comportementales (e-tracking)
 * pour une question spécifique du questionnaire.
 */
window.QuestionTracker = class QuestionTracker {
    constructor(questionId, position) {
        this.questionId = questionId;
        this.position = position;

        // --- GESTION DU TEMPS ---
        this._startTime = Date.now();            // Moment précis de l'affichage de la question
        this._firstInteractionAt = null;         // Utilisé pour calculer la latence (temps de réflexion initial)
        this._lastActivityAt = Date.now();       // Timestamp de la toute dernière action (clic ou souris)

        // --- COMPTEURS COMPORTEMENTAUX ---
        this.nbClics = 0;                        // Nombre total de clics sur la page
        this.nbChangements = 0;                  // Nombre de fois où l'utilisateur a changé de réponse
        this.nbClicsHorsCible = 0;               // Clics dans le "vide" (ni bouton, ni option) : signe d'agacement ou d'erreur
        this.nbPauses = 0;                       // Nombre de fois où l'utilisateur est resté inactif

        // --- ÉTATS INTERNES ---
        this._currentAnswer = null;              // Réponse actuellement sélectionnée (lettre A, B, C...)
        this._inPause = false;                   // État d'inactivité actuel
        this._lastMousePos = null;               // Dernière position connue de la souris {x, y}
        this.mouseTrail = [];                    // Tableau de points [temps, x, y] pour reconstruire le mouvement

        // --- PARAMÈTRES (CONSTANTES) ---
        this._PAUSE_THRESHOLD_MS = 5000;         // Temps d'immobilité (5s) pour compter une "pause"
        this._MOUSE_SAMPLE_MS = 300;             // Fréquence d'enregistrement de la souris (toutes les 300ms)
        this._MOUSE_MAX_POINTS = 150;            // Limite de points pour éviter de saturer la mémoire et la BDD

        this._bindClickTracking();
        this._bindMouseTracking();
        this._startPauseDetection();
    }

    /**
     * Enregistre le choix d'une option par l'utilisateur.
     * @param {string} lettre - La lettre de l'option choisie.
     */
    recordAnswerChange(lettre) {
        this._touch(); 
        if (this._currentAnswer !== null && this._currentAnswer !== lettre) {
            this.nbChangements++;
        }
        this._currentAnswer = lettre;
    }

    /**
     * Compile toutes les données mesurées dans un objet prêt pour l'envoi au serveur.
     */
    collect() {
        return {
            id_question:          this.questionId,
            position:             this.position,
            temps_total_ms:       Date.now() - this._startTime,
            latence_ms:           this._firstInteractionAt ? this._firstInteractionAt - this._startTime : 0,
            nb_clics:             this.nbClics,
            nb_changements:       this.nbChangements,
            nb_clics_hors_cible:  this.nbClicsHorsCible,
            nb_pauses:            this.nbPauses,
            suivi_souris:         JSON.stringify(this.mouseTrail),
        };
    }

    /**
     * Nettoyage impératif des écouteurs et des intervalles.
     */
    destroy() {
        document.removeEventListener('click',     this._onDocClick);
        document.removeEventListener('mousemove', this._onMouseMove);
        clearInterval(this._mouseSampleInterval);
        clearInterval(this._pauseInterval);
    }

    /**
     * Marque le moment de la première interaction et met à jour l'horloge d'activité.
     * Interne.
     */
    _touch() {
        const now = Date.now();
        if (this._firstInteractionAt === null) this._firstInteractionAt = now;
        this._lastActivityAt = now;
    }

    /**
     * Initialise le suivi des clics.
     */
    _bindClickTracking() {
        this._onDocClick = (e) => {
            this._touch();
            this.nbClics++;
            
            // Si le clic ne provient pas d'un élément marqué avec [data-choice] (boutons, options),
            // on considère que c'est un clic "hors cible".
            if (!e.target.closest('[data-choice]')) {
                this.nbClicsHorsCible++;
            }
        };
        document.addEventListener('click', this._onDocClick, { passive: true });
    }

    /**
     * Initialise le suivi du mouvement de la souris (échantillonnage temporel).
     */
    _bindMouseTracking() {
        this._onMouseMove = (e) => {
            this._touch();
            this._lastMousePos = { x: Math.round(e.clientX), y: Math.round(e.clientY) };
        };
        document.addEventListener('mousemove', this._onMouseMove, { passive: true });

        this._mouseSampleInterval = setInterval(() => {
            if (!this._lastMousePos) return;
            
            const t = Date.now() - this._startTime;
            this.mouseTrail.push([t, this._lastMousePos.x, this._lastMousePos.y]);
            
            if (this.mouseTrail.length > this._MOUSE_MAX_POINTS) {
                this.mouseTrail.shift();
            }
        }, this._MOUSE_SAMPLE_MS);
    }

    /**
     * Analyse l'inactivité de l'utilisateur.
     */
    _startPauseDetection() {
        this._pauseInterval = setInterval(() => {
            const idle = Date.now() - this._lastActivityAt;
            
            if (idle >= this._PAUSE_THRESHOLD_MS && !this._inPause) {
                this.nbPauses++;
                this._inPause = true; 
            } else if (idle < this._PAUSE_THRESHOLD_MS && this._inPause) {
                this._inPause = false; 
            }
        }, 1000); 
    }
};