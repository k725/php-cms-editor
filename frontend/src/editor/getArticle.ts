import {getArticleID} from "../util/getArticleID";
import {fetchArticleAsync} from "../api/fetchArticle";
import {addParts} from "../parts/add";
import {createHeadingParts} from "../parts/heading";
import {createTextParts} from "../parts/text";
import {createReferenceParts} from "../parts/reference";
import {setupPartsEditEvents} from "./events";

export const buildArticle = async () => {
    const articleId = getArticleID();
    const result = await fetchArticleAsync(articleId);

    document.getElementById('article_id').innerText = result.data.id;
    document.getElementById('title').value =  result.data.title;
    document.getElementById('description').value = result.data.description;

    result.data.parts.forEach((data) => {
        switch (data.name) {
            case "heading":
                const headElem = createHeadingParts(data.partsId, data.data);
                addParts(headElem);
                setupPartsEditEvents(headElem);
                break;
            case "text":
                const textElem = createTextParts(data.partsId, data.data);
                addParts(textElem);
                setupPartsEditEvents(textElem);
                break;
            case "reference":
                const refElem = createReferenceParts(
                    data.partsId,
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
