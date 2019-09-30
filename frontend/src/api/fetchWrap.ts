export interface IEmptyResponse {
    statusCode?: number;
}

export class FetchWrap {
    /**
     * @param url Access URL
     */
    public static async getJsonAsync<T>(url: string): Promise<Partial<T>> {
        try {
            const result = await fetch(url);
            const resultJson = await result.json();
            return resultJson as T;
        } catch (e) {
            console.error(e);
            return {} as T;
        }
    }

    /**
     * @param url Access URL
     * @param data Post data
     */
    public static async postJsonAsync<ReqT, ResT>(url: string, data: ReqT): Promise<Partial<ResT>> {
        try {
            const result = await fetch(url, {
                method: "POST",
                body: JSON.stringify(data),
            });
            const resultJson = await result.json();
            return resultJson as ResT;
        } catch (e) {
            console.error(e);
            return {} as ResT;
        }
    }
}