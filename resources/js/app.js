import './bootstrap';
import QuestionnaireLogic from './questionnaire-logic';

document.addEventListener('alpine:init', () => {
    Alpine.data('questionnaireHandler', QuestionnaireLogic);
});
