window.QuestionTracker = class QuestionTracker {
    constructor(questionId, position) {
        this.questionId = questionId;
        this.position = position;
        this._startTime = Date.now();
        this._firstInteractionAt = null;
        this._lastActivityAt = Date.now();
        this.nbClics = 0;
        this.nbChangements = 0;
        this.nbClicsHorsCible = 0;
        this.nbPauses = 0;
        this._currentAnswer = null;
        this._inPause = false;
        this._lastMousePos = null;
        this.mouseTrail = [];
        this._PAUSE_THRESHOLD_MS = 5000;
        this._MOUSE_SAMPLE_MS = 300;
        this._MOUSE_MAX_POINTS = 150;

        this._bindClickTracking();
        this._bindMouseTracking();
        this._startPauseDetection();
    }

    recordAnswerChange(lettre) {
        this._touch();
        if (this._currentAnswer !== null && this._currentAnswer !== lettre) {
            this.nbChangements++;
        }
        this._currentAnswer = lettre;
    }

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

    destroy() {
        document.removeEventListener('click',     this._onDocClick);
        document.removeEventListener('mousemove', this._onMouseMove);
        clearInterval(this._mouseSampleInterval);
        clearInterval(this._pauseInterval);
    }

    _touch() {
        const now = Date.now();
        if (this._firstInteractionAt === null) this._firstInteractionAt = now;
        this._lastActivityAt = now;
    }

    _bindClickTracking() {
        this._onDocClick = (e) => {
            this._touch();
            this.nbClics++;
            if (!e.target.closest('[data-choice]')) this.nbClicsHorsCible++;
        };
        document.addEventListener('click', this._onDocClick, { passive: true });
    }

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
            if (this.mouseTrail.length > this._MOUSE_MAX_POINTS) this.mouseTrail.shift();
        }, this._MOUSE_SAMPLE_MS);
    }

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
