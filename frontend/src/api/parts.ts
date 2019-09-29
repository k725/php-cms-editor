export const addPartsAsync = async (id: number, data: object) => {
    try {
        data.mode = "add";
        const result = await fetch(`/api/articles/${id}`, {
            method: "post",
            body: JSON.stringify(data),
        });
        return await result.json();
    } catch (e) {
        console.log(e);
        return {

        };
    }
};

export const deletePartsAsync = async (id: number, partsId: number) => {
    try {
        const data = {
            mode: "delete",
            id: partsId,
        };
        const result = await fetch(`/api/articles/${id}`, {
            method: "post",
            body: JSON.stringify(data),
        });
        return await result.json();
    } catch (e) {
        console.log(e);
        return {

        };
    }
};

export const updatePartsOrderAsync = async (id: number, partsId: number, oldIndex: number, newIndex: number) => {
    try {
        const data = {
            mode: "update",
            partsId: partsId,
            old: oldIndex,
            new: newIndex,
        };
        const result = await fetch(`/api/articles/${id}`, {
            method: "post",
            body: JSON.stringify(data),
        });
        return await result.json();
    } catch (e) {
        console.log(e);
        return {

        };
    }
};
