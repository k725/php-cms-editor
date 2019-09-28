import {createHeadingParts} from "../../parts/heading";
import {addParts} from "../../parts/add";
import {setupPartsEditEvents} from "../events";
import {createTextParts} from "../../parts/text";
import {createReferenceParts} from "../../parts/reference";
import * as M from "materialize-css";
import {addPartsAsync} from "../../api/parts";
import {getArticleID} from "../../util/getArticleID";

const setupHeadingEvents = () => {
    // Add event
    document.getElementById('heading_add').addEventListener('click', async () => {
        const headingValue = document.getElementById('heading').value;
        if (headingValue.trim() === "") {
            return;
        }

        const data = {
            type: "heading",
            data: headingValue,
        };
        const articleId = getArticleID();
        const result = await addPartsAsync(articleId, data);
        const partsHeader = createHeadingParts(result.data.id, headingValue);
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
    document.getElementById('main_text_add').addEventListener('click', async () => {
        const textValue = document.getElementById('main_text').value;
        if (textValue.trim() === "") {
            return;
        }

        const data = {
            type: "text",
            data: textValue,
        };
        const articleId = getArticleID();
        const result = await addPartsAsync(articleId, data);
        const partsText = createTextParts(result.data.id, textValue);
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
    document.getElementById('reference_article_add').addEventListener('click', async () => {
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

        const data = {
            type: "reference",
            data: selectArticle,
        };
        const articleId = getArticleID();
        await addPartsAsync(articleId, data);
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