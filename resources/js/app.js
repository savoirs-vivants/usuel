import './bootstrap';
import QuestionnaireLogic from './questionnaire-logic';
import selectionTable from './selection';
import statsHandler from './stats-logic';

document.addEventListener('alpine:init', () => {
    Alpine.data('statsHandler', statsHandler);
});

document.addEventListener('alpine:init', () => {
    Alpine.data('selectionTable', selectionTable);
});

document.addEventListener('alpine:init', () => {
    Alpine.data('questionnaireHandler', QuestionnaireLogic);
});
