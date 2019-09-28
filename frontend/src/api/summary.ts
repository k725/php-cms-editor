export const upadteArticleSummaryAsync = async (id: number, title: string, description: string) => {
    try {
        const data = {
            mode: "summary",
            title: title,
            description: description,
        };
        const result = await fetch(`/api/articles/${id}`, {
            method: "post",
            body: JSON.stringify(data),
        });
        return await result.json();
    } catch (e) {
        console.log(e);
        return {

        };
    }
};