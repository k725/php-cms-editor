import {createHeadingParts} from "../../parts/heading";
import {addParts} from "../../parts/add";
import {setupPartsEditEvents} from "../events";
import {createTextParts} from "../../parts/text";
import {createReferenceParts} from "../../parts/reference";
import * as M from "materialize-css";

const setupHeadingEvents = () => {
    // Add event
    document.getElementById('heading_add').addEventListener('click', () => {
        const headingValue = document.getElementById('heading').value;
        const partsHeader = createHeadingParts(headingValue);
        addParts(partsHeader);
        setupPartsEditEvents(partsHeader);
    }, false);

    // Reset event
    document.getElementById('heading_reset').addEventListener('click', () => {
        document.getElementById('heading').value = '';
    }, false);
};

const setupTextEvents = () => {
    // Add event
    document.getElementById('main_text_add').addEventListener('click', () => {
        const textValue = document.getElementById('main_text').value;
        const partsText = createTextParts(textValue);
        addParts(partsText);
        setupPartsEditEvents(partsText);
    }, false);

    // Reset event
    document.getElementById('main_text_reset').addEventListener('click', () => {
        document.getElementById('main_text').value = '';
    }, false);
};

const setupReferenceEvents = () => {
    // Add event
    document.getElementById('reference_article_add').addEventListener('click', () => {
        const selectArticle = document.getElementById('reference_article').selectedIndex;
        if (selectArticle === -1) {
            console.log('Not selected!');
            return;
        }

        const titleValue = 'a';
        const descValue = 'i';
        const linkValue = 'u';
        const partsReference = createReferenceParts(titleValue, descValue, linkValue);
        addParts(partsReference);
        setupPartsEditEvents(partsReference);
    }, false);

    // Reset event
    document.getElementById('reference_article_reset').addEventListener('click', () => {
        document.getElementById('reference_article').selectedIndex = -1;
        M.FormSelect.init(document.getElementById('reference_article'), {});
    }, false);
};

export const setupInsertEditorEvents = () => {
    setupHeadingEvents();
    setupTextEvents();
    setupReferenceEvents();
};