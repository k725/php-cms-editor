import {throttle} from "lodash";
import {upadteArticleSummaryAsync} from "../../api/summary";
import {getArticleID} from "../../util/getArticleID";

export const setupSummaryEvents = () => {
    const articleId = getArticleID();
    let lastTitle = '';
    let lastDescription = '';
    const updateSummary = throttle(async () => {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        if (lastTitle === title && lastDescription == description) {
            return;
        }
        lastTitle = title;
        lastDescription = description;
        await upadteArticleSummaryAsync(articleId, title, description);
    }, 1000);

    // document.getElementById('title').addEventListener('keydown', updateSummary, false);
    document.getElementById('title').addEventListener('focusout', updateSummary, false);
    // document.getElementById('description').addEventListener('keydown', updateSummary, false);
    document.getElementById('description').addEventListener('focusout', updateSummary, false);
};
