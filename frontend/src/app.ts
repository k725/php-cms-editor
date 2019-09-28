// Scripts
import {setupPartsEditEvents} from "./editor/events";
import {setupInsertEditorEvents} from "./editor/insert/events";
import {setupMaterialize} from "./materialize/init";
import {setupSummaryEvents} from "./editor/summary/events";

// Styles
import 'materialize-css/dist/css/materialize.min.css';
import './style/main.scss';
import {buildArticle} from "./editor/getArticle";

document.addEventListener('DOMContentLoaded', async () => {
    setupMaterialize();
    setupInsertEditorEvents();
    setupSummaryEvents();

    const selector = '.parts-header, .parts-text, .parts-reference';
    document.querySelectorAll(selector).forEach(setupPartsEditEvents);

    await buildArticle();
}, false);
