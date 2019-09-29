export const fetchArticleAsync = async (id: number) => {
    try {
        const result = await fetch(`/api/articles/${id}`);
        return await result.json();
    } catch (e) {
        console.log(e);
        return {

        };
    }
};

let articles = [];
export const fetchAllArticles = async () => {
    try {
        if (articles.length != 0) {
            return articles;
        }
        const result = await fetch(`/api/articles`);
        const json = await result.json();
        articles = json;
        return json;
    } catch (e) {
        console.log(e);
        return [];
    }
};
