import {fetchAllArticles} from "../../api/fetchArticle";
import * as M from "materialize-css";

export const buildSummaryList = async () => {
    const select = document.getElementById('reference_article');
    const result = await fetchAllArticles();
    result.data.forEach((data) => {
        const opt = document.createElement('option');
        opt.value = data.id;
        opt.innerHTML = `(ID: ${data.id}) ${data.title}`;
        select.appendChild(opt);
    });
    M.FormSelect.init(select, {});
};
