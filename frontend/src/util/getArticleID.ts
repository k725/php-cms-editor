/**
 * Return a article id
 */
export const getArticleID = (): number => {
    const pathSplit = window.location.pathname.split('/');
    if (pathSplit.length != 4) {
        return -1;
    }
    return parseInt(pathSplit[2], 10);
};