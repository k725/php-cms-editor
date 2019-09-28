export const getArticleID = () => {
    const pathSplit = window.location.pathname.split('/');
    if (pathSplit.length != 4) {
        return -1;
    }
    return parseInt(pathSplit[2], 10);
};