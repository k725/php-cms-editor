// Styles
import 'materialize-css/dist/css/materialize.min.css';
import './style/main.scss';

// Scripts
import {setupInsertEditorEvents} from "./editor/insert/events";
import {setupMaterialize} from "./materialize/init";
import {setupSummaryEvents} from "./editor/summary/events";
import {buildArticle} from "./editor/getArticle";
import {buildSummaryList} from "./editor/summary/list";

document.addEventListener('DOMContentLoaded', async () => {
    setupMaterialize();
    setupInsertEditorEvents();
    setupSummaryEvents();
    await buildArticle();
    await buildSummaryList();
}, false);
