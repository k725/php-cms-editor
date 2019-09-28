import {getArticleID} from "../util/getArticleID";
import {fetchArticleAsync} from "../api/fetchArticle";

export const buildArticle = async () => {
    const articleId = getArticleID();
    const result = await fetchArticleAsync(articleId);

    document.getElementById('article_id').innerText = result.data.id;
    document.getElementById('title').value =  result.data.title;
    document.getElementById('description').value = result.data.description;
};
