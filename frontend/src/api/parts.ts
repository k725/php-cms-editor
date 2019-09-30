import {FetchWrap, IEmptyResponse} from "./fetchWrap";

export enum PartsType {
    Heading = "heading",
    Text = "text",
    Reference = "reference",
}

interface IAddPartsResponse {
    statusCode: number;
    data: {
        id: number;
    };
}

interface IAddPartsRequest {
    mode: "add",
    type: PartsType;
    data: string|number;
}

interface IUpdateOrderPartsRequest {
    mode: "update",
    partsId: number; // @todo change name!!!
    old: number,
    new: number,
}

interface IDeletePartsRequest {
    mode: "delete",
    id: number;
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
    ): Promise<Partial<IAddPartsResponse>> {
        return await FetchWrap.postJsonAsync<IAddPartsRequest, IAddPartsResponse>(`/api/articles/${articleId}`, data);
    }

    /**
     * Update the order of article parts
     * @param articleId
     * @param data
     */
    public static async updateOrderAsync(
        articleId: number,
        data: IUpdateOrderPartsRequest
    ): Promise<Partial<IEmptyResponse>> {
        return await FetchWrap.postJsonAsync<IUpdateOrderPartsRequest, IEmptyResponse>(`/api/articles/${articleId}`, data);
    }

    /**
     * Delete article parts the article
     * @param articleId
     * @param data
     */
    public static async deleteAsync(
        articleId: number,
        data: IDeletePartsRequest
    ): Promise<Partial<IEmptyResponse>> {
        return await FetchWrap.postJsonAsync<IDeletePartsRequest, IEmptyResponse>(`/api/articles/${articleId}`, data);
    }
}