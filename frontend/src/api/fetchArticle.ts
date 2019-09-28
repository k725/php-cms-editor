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

export const fetchAllArticles = async () => {
    try {
        const result = await fetch(`/api/articles`);
        return await result.json();
    } catch (e) {
        console.log(e);
        return [];
    }
};
