import {FetchWrap, IBooleanResponse} from "./fetchWrap";

export enum PartsType {
    Heading = "heading",
    Text = "text",
    Reference = "reference",
}

interface IAddPartsRequest {
    mode: "add",
    type: PartsType;
    data: string|number;
}

interface IUpdateOrderPartsRequest {
    mode: "update",
    oldOrder: number,
    newOrder: number,
}

interface IDeletePartsRequest {
    mode: "delete",
    order: number;
}

export class Parts {
    /**
     * Add article parts the article
     * @param articleId
     * @param data
     */
    public static async addAsync(
        articleId: number,
        data: IAddPartsRequest
    ): Promise<Partial<IBooleanResponse>> {
        return await FetchWrap.postJsonAsync<IAddPartsRequest, IBooleanResponse>(`/api/articles/${articleId}`, data);
    }

    /**
     * Update the order of article parts
     * @param articleId
     * @param data
     */
    public static async updateOrderAsync(
        articleId: number,
        data: IUpdateOrderPartsRequest
    ): Promise<Partial<IBooleanResponse>> {
        return await FetchWrap.postJsonAsync<IUpdateOrderPartsRequest, IBooleanResponse>(`/api/articles/${articleId}`, data);
    }

    /**
     * Delete article parts the article
     * @param articleId
     * @param data
     */
    public static async deleteAsync(
        articleId: number,
        data: IDeletePartsRequest
    ): Promise<Partial<IBooleanResponse>> {
        return await FetchWrap.postJsonAsync<IDeletePartsRequest, IBooleanResponse>(`/api/articles/${articleId}`, data);
    }
}