import {throttle} from "lodash";
import {getArticleID} from "../../util/getArticleID";
import {Article} from "../../api/article";

export const setupSummaryEvents = () => {
    const articleId = getArticleID();
    let lastTitle = '';
    let lastDescription = '';
    const updateSummary = throttle(async () => {
        const title = (<HTMLInputElement>document.getElementById('title')).value;
        const description = (<HTMLInputElement>document.getElementById('description')).value;
        if (lastTitle === title && lastDescription == description) {
            return;
        }
        lastTitle = title;
        lastDescription = description;
        await Article.updateSummary(articleId, {
            title: title,
            description: description,
            mode: "summary",
        });
    }, 1500);

    document.getElementById('title').addEventListener('keydown', updateSummary, false);
    document.getElementById('title').addEventListener('focusout', updateSummary, false);
    document.getElementById('description').addEventListener('keydown', updateSummary, false);
    document.getElementById('description').addEventListener('focusout', updateSummary, false);
};
