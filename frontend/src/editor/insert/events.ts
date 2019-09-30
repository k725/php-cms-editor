import * as M from "materialize-css";
import {createHeadingParts} from "../../parts/heading";
import {addParts} from "../../parts/add";
import {setupPartsEditEvents} from "../events";
import {createTextParts} from "../../parts/text";
import {createReferenceParts} from "../../parts/reference";
import {Parts, PartsType} from "../../api/parts";
import {getArticleID} from "../../util/getArticleID";
import {Article} from "../../api/article";

const setupHeadingEvents = () => {
    // Add event
    document.getElementById('heading_add').addEventListener('click', async () => {
        const headingValue = (<HTMLInputElement>document.getElementById('heading')).value;
        if (headingValue.trim() === "") {
            return;
        }

        const articleId = getArticleID();
        const result = await Parts.addAsync(articleId, {
            mode: "add",
            type: PartsType.Heading,
            data: headingValue,
        });

        const partsHeader = createHeadingParts(result.data.id, headingValue);
        addParts(partsHeader);
        setupPartsEditEvents(partsHeader);
        (<HTMLInputElement>document.getElementById('heading')).value = '';
    }, false);

    // Reset event
    document.getElementById('heading_reset').addEventListener('click', () => {
        (<HTMLInputElement>document.getElementById('heading')).value = '';
    }, false);
};

const setupTextEvents = () => {
    // Add event
    document.getElementById('main_text_add').addEventListener('click', async () => {
        const textValue = (<HTMLInputElement>document.getElementById('main_text')).value;
        if (textValue.trim() === "") {
            return;
        }

        const articleId = getArticleID();
        const result = await Parts.addAsync(articleId, {
            mode: "add",
            type: PartsType.Text,
            data: textValue,
        });

        const partsText = createTextParts(result.data.id, textValue);
        addParts(partsText);
        setupPartsEditEvents(partsText);
        (<HTMLInputElement>document.getElementById('main_text')).value = '';
    }, false);

    // Reset event
    document.getElementById('main_text_reset').addEventListener('click', () => {
        (<HTMLInputElement>document.getElementById('main_text')).value = '';
    }, false);
};

const setupReferenceEvents = () => {
    // Add event
    document.getElementById('reference_article_add').addEventListener('click', async () => {
        const selectArticle = (<HTMLSelectElement>document.getElementById('reference_article')).selectedIndex;
        if (selectArticle === -1) {
            console.log('Not selected!');
            return;
        }

        const articleId = getArticleID();
        const article = await Article.fetchAsync(selectArticle);

        const titleValue = article.data.title;
        const descValue = article.data.description;
        const linkValue = `/articles/${article.data.id}`;

        const result = await Parts.addAsync(articleId, {
            mode: "add",
            type: PartsType.Reference,
            data: selectArticle,
        });

        const partsReference = createReferenceParts(result.data.id, titleValue, descValue, linkValue);
        addParts(partsReference);
        setupPartsEditEvents(partsReference);

        (<HTMLSelectElement>document.getElementById('reference_article')).selectedIndex = -1;
        M.FormSelect.init(document.getElementById('reference_article'), {});
    }, false);

    // Reset event
    document.getElementById('reference_article_reset').addEventListener('click', () => {
        (<HTMLSelectElement>document.getElementById('reference_article')).selectedIndex = -1;
        M.FormSelect.init(document.getElementById('reference_article'), {});
    }, false);
};

export const setupInsertEditorEvents = () => {
    setupHeadingEvents();
    setupTextEvents();
    setupReferenceEvents();
};