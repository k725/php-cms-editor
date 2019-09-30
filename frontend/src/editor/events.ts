import {Parts} from "../api/parts";
import {getArticleID} from "../util/getArticleID";

const getPartsNode = (e) => {
    return e.currentTarget.parentNode.parentNode; // @todo よろしくない
};

const getCurrentOrder = (e) => {
    return [...e.parentElement.children].indexOf(e) + 1;
};

const hasPreviousNode = (e): boolean => {
    const nodes = e.parentElement.children;
    const nodeIndex = [...nodes].indexOf(e);
    return nodeIndex > 0;
};

const hasNextNode = (e) => {
    const nodes = e.parentElement.children;
    const maxIndex = nodes.length - 1;
    const nodeIndex = [...nodes].indexOf(e);
    return nodeIndex < maxIndex;
};

export const setupPartsEditEvents = (elem) => {
    // Mouse enter event
    elem.addEventListener('mouseenter', () => {
        const hiddenEditor = document.getElementById('hidden-editor').cloneNode(true) as HTMLElement; // @todo
        hiddenEditor.id = 'editor';
        hiddenEditor.style.display = 'block';
        elem.appendChild(hiddenEditor);

        const visibleEditor = document.getElementById('editor');
        if (!hasPreviousNode(visibleEditor.parentNode)) {
            document.getElementById('up').classList.add('disabled');
        }
        if (!hasNextNode(visibleEditor.parentNode)) {
            document.getElementById('down').classList.add('disabled');
        }

        // Up button
        document.getElementById('up').addEventListener('click', async (e) => {
            const partsNode = getPartsNode(e);
            const articleId = getArticleID();
            const partsId = parseInt(partsNode.dataset.id, 10);

            const order = getCurrentOrder(partsNode);
            await Parts.updateOrderAsync(articleId, {
                mode: "update",
                partsId: partsId,
                old: order,
                new: order - 1,
            });

            partsNode.previousElementSibling.before(partsNode);
        }, false);

        // Down button
        document.getElementById('down').addEventListener('click', async (e) => {
            const partsNode = getPartsNode(e);
            const articleId = getArticleID();
            const partsId = parseInt(partsNode.dataset.id, 10);

            const order = getCurrentOrder(partsNode);
            await Parts.updateOrderAsync(articleId, {
                mode: "update",
                partsId: partsId,
                old: order,
                new: order + 1,
            });

            partsNode.nextElementSibling.after(partsNode);
        }, false);

        // Delete button
        document.getElementById('delete').addEventListener('click', async (e) => {
            const partsNode = getPartsNode(e);
            const articleId = getArticleID();

            await Parts.deleteAsync(articleId, {
                mode: "delete",
                id: parseInt(partsNode.dataset.id, 10),
            });

            partsNode.remove();
        }, false);
    }, false);

    // Mouse leave event
    elem.addEventListener('mouseleave', () => {
        document.getElementById('editor').remove();
    }, false);
};