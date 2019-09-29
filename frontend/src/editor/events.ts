import {deletePartsAsync, updatePartsOrderAsync} from "../api/parts";
import {getArticleID} from "../util/getArticleID";

const getPartsNode = (e) => {
    return e.currentTarget.parentNode.parentNode; // @todo よろしくない
};

const getCurrentOrder = (e) => {
    return [...e.parentElement.children].indexOf(e) + 1;
};

export const setupPartsEditEvents = (elem) => {
    // Mouse enter event
    elem.addEventListener('mouseenter', () => {
        const editor = document.getElementById('hidden-editor').cloneNode(true) as HTMLElement; // @todo
        editor.id = 'editor';
        editor.style.display = 'block';
        elem.appendChild(editor);

        // Up button
        document.getElementById('up').addEventListener('click', async (e) => {
            const partsNode = getPartsNode(e);
            const articleId = getArticleID();
            const partsId = parseInt(partsNode.dataset.id, 10);

            const order = getCurrentOrder(partsNode);
            await updatePartsOrderAsync(articleId, partsId, order, order - 1);

            partsNode.previousElementSibling.before(partsNode);

        }, false);

        // Down button
        document.getElementById('down').addEventListener('click', async (e) => {
            const partsNode = getPartsNode(e);
            const articleId = getArticleID();
            const partsId = parseInt(partsNode.dataset.id, 10);

            const order = getCurrentOrder(partsNode);
            await updatePartsOrderAsync(articleId, partsId, order, order + 1);

            partsNode.nextElementSibling.after(partsNode);
        }, false);

        // Delete button
        document.getElementById('delete').addEventListener('click', async (e) => {
            const partsNode = getPartsNode(e);
            const articleId = getArticleID();
            await deletePartsAsync(articleId, parseInt(partsNode.dataset.id, 10));
            partsNode.remove();
        }, false);
    }, false);

    // Mouse leave event
    elem.addEventListener('mouseleave', () => {
        document.getElementById('editor').remove();
    }, false);
};