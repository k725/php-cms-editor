import {FetchWrap, IBooleanResponse} from "./fetchWrap";
import {PartsType} from "./parts";

interface IArticleSummary {
    id: number;
    title: string;
    createdAt: string;
    updatedAt: string;
}

interface IArticlesResponse {
    statusCode: number;
    data: IArticleSummary[];
}

interface IReferenceParts {
    id: number;
    title: string;
    description: string;
}

interface IArticleParts {
    data: string | IReferenceParts;
    name: PartsType;
}

interface IArticleResponse {
    statusCode: number;
    data: {
        id: number;
        title: string;
        description: string;
        createdAt: string;
        updatedAt: string;
        parts: IArticleParts[];
    };
}

interface IUpdateArticleSummaryRequest {
    mode: "summary";
    title: string;
    description: string;
}

export class Article {
    /**
     * Fetch All articles
     */
    public static async fetchAllAsync(): Promise<Partial<IArticlesResponse>> {
        return await FetchWrap.getJsonAsync<IArticlesResponse>(`/api/articles`);
    }

    /**
     * Fetch single article
     * @param articleId
     */
    public static async fetchAsync(articleId: number): Promise<Partial<IArticleResponse>> {
        return await FetchWrap.getJsonAsync<IArticleResponse>(`/api/articles/${articleId}`);
    }

    /**
     * Update article summary data
     * @param articleId
     * @param data
     */
    public static async updateSummary(
        articleId: number,
        data: IUpdateArticleSummaryRequest
    ): Promise<Partial<IBooleanResponse>> {
        return await FetchWrap.postJsonAsync<IUpdateArticleSummaryRequest, IBooleanResponse>(`/api/articles/${articleId}`, data);
    }
}