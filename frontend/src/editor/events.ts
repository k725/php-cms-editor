const getPartsNode = (e) => {
    return e.currentTarget.parentNode.parentNode; // @todo よろしくない
};

export const setupPartsEditEvents = (elem) => {
    elem.addEventListener('mouseenter', () => {
        const editor = document.getElementById('hidden-editor').cloneNode(true) as HTMLElement; // @todo
        editor.id = 'editor';
        editor.style.display = 'block';
        elem.appendChild(editor);

        document.getElementById('up').addEventListener('click', (e) => {
            const partsNode = getPartsNode(e);
            partsNode.previousElementSibling.before(partsNode);
        }, false);
        document.getElementById('down').addEventListener('click', (e) => {
            const partsNode = getPartsNode(e);
            partsNode.nextElementSibling.after(partsNode);
        }, false);
        document.getElementById('delete').addEventListener('click', (e) => {
            getPartsNode(e).remove();
        }, false);
    }, false);
    elem.addEventListener('mouseleave', () => {
        document.getElementById('editor').remove();
    }, false);
};