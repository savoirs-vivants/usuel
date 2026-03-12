/**
 * Gère la synthèse vocale, le suivi comportemental (tracking) et la transition entre questions.
 */
export default (initialData) => ({
    tracker: null,
    audioEnabled: initialData.audioEnabled,
    isSpeaking: false,
    speechLang: initialData.speechLang,

    langMap: {
        fr: 'fr-FR', en: 'en-GB', es: 'es-ES',
        de: 'de-DE', ar: 'ar-SA', ru: 'ru-RU', tr: 'tr-TR',
    },

    /**
     * Initialisation du composant Alpine
     */
    init() {
        this.startTracker();
        if (this.audioEnabled) {
            setTimeout(() => this.speakQuestion(), 400);
        }
    },

    /**
     * Lit un texte via les haut-parleurs
     * @param {string} text 
     */
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

    /**
     * Construit le texte de la question et des options à partir du DOM
     */
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

    /**
     * Initialise une nouvelle instance du QuestionTracker
     * Nécessite que l'élément #q-meta-data soit présent dans le DOM (généré par Blade)
     */
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

    /**
     * Enregistre un changement de réponse dans le tracker sans envoyer de données au serveur
     */
    recordChoice(lettre) {
        if (this.tracker) this.tracker.recordAnswerChange(lettre);
    },

    /**
     * Valide la réponse ou passe la question
     * Gère la collecte des données de tracking et l'envoi vers Livewire ($wire)
     */
    async processAction(actionType, letra = null) {
        this.stopSpeech(); // On coupe la voix dès qu'on clique sur un bouton d'action
        
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