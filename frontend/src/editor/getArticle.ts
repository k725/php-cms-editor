import {getArticleID} from "../util/getArticleID";
import {Article} from "../api/article";
import {addParts} from "../parts/add";
import {createHeadingParts} from "../parts/heading";
import {createTextParts} from "../parts/text";
import {createReferenceParts} from "../parts/reference";
import {setupPartsEditEvents} from "./events";

export const buildArticle = async () => {
    const articleId = getArticleID();
    const result = await Article.fetchAsync(articleId);

    document.getElementById('article_id').innerText = result.data.id.toString();
    (<HTMLInputElement>document.getElementById('title')).value =  result.data.title;
    (<HTMLInputElement>document.getElementById('description')).value = result.data.description;

    result.data.parts.forEach((data) => {
        switch (data.name) {
            case "heading":
                if (typeof data.data !== "string") {
                    return;
                }
                const headElem = createHeadingParts(data.data);
                addParts(headElem);
                setupPartsEditEvents(headElem);
                break;
            case "text":
                if (typeof data.data !== "string") {
                    return;
                }
                const textElem = createTextParts(data.data);
                addParts(textElem);
                setupPartsEditEvents(textElem);
                break;
            case "reference":
                if (typeof data.data === "string") {
                    return;
                }
                const refElem = createReferenceParts(
                    data.data.title,
                    data.data.description,
                    `/articles/${data.data.id}`,
                );
                addParts(refElem);
                setupPartsEditEvents(refElem);
                break;
            default:
                console.log("invalid data", data);
                break;
        }
    });
};
