import {Article} from "../../api/article";
import * as M from "materialize-css";

export const buildSummaryList = async () => {
    const select = document.getElementById('reference_article');
    const result = await Article.fetchAllAsync();
    result.data.forEach((data) => {
        const opt = document.createElement('option');
        opt.value = data.id.toString();
        opt.innerHTML = `(ID: ${data.id}) ${data.title}`;
        select.appendChild(opt);
    });
    M.FormSelect.init(select, {});
};
