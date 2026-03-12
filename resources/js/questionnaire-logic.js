export default (initialData) => ({
    tracker: null,
    audioEnabled: initialData.audioEnabled,
    isSpeaking: false,
    speechLang: initialData.speechLang,

    langMap: {
        fr: 'fr-FR', en: 'en-GB', es: 'es-ES',
        de: 'de-DE', ar: 'ar-SA', ru: 'ru-RU', tr: 'tr-TR',
    },

    init() {
        this.startTracker();
        if (this.audioEnabled) {
            setTimeout(() => this.speakQuestion(), 400);
        }
    },

    speak(text) {
        if (!this.audioEnabled || !('speechSynthesis' in window) || !text) return;
        window.speechSynthesis.cancel();
        this.isSpeaking = true;
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = this.langMap[this.speechLang] ?? 'fr-FR';
        utter.rate = 0.92;
        utter.onend = () => { this.isSpeaking = false; };
        utter.onerror = () => { this.isSpeaking = false; };
        window.speechSynthesis.speak(utter);
    },

    speakQuestion() {
        const questionEl = document.getElementById('audio-intitule');
        const choixEls = document.querySelectorAll('[data-audio-choix]');
        if (!questionEl) return;
        let text = questionEl.innerText.trim();
        choixEls.forEach((el, i) => {
            text += `… ${String.fromCharCode(65 + i)} : ${el.innerText.trim()}`;
        });
        this.speak(text);
    },

    stopSpeech() {
        if ('speechSynthesis' in window) window.speechSynthesis.cancel();
        this.isSpeaking = false;
    },

    startTracker() {
        if (this.tracker) this.tracker.destroy();
        const el = document.getElementById('q-meta-data');
        if (el?.dataset.qid) {
            this.tracker = new window.QuestionTracker(
                parseInt(el.dataset.qid),
                parseInt(el.dataset.pos)
            );
        }
    },

    recordChoice(lettre) {
        if (this.tracker) this.tracker.recordAnswerChange(lettre);
    },

    async processAction(actionType, letra = null) {
        this.stopSpeech();
        if (letra) this.$wire.choisir(letra);

        const data = this.tracker ? this.tracker.collect() : {};
        if (this.tracker) this.tracker.destroy();
        this.tracker = null;

        if (actionType === 'valider') {
            await this.$wire.validerAvecTracking(data);
        } else {
            await this.$wire.jeSaisPasAvecTracking(data);
        }

        this.$nextTick(() => {
            this.startTracker();
            if (this.audioEnabled) setTimeout(() => this.speakQuestion(), 300);
        });
    }
});
