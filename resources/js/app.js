import './bootstrap';
import QuestionnaireLogic from './questionnaire-logic';
import selectionTable from './selection';

document.addEventListener('alpine:init', () => {
    Alpine.data('selectionTable', selectionTable);
});

document.addEventListener('alpine:init', () => {
    Alpine.data('questionnaireHandler', QuestionnaireLogic);
});
