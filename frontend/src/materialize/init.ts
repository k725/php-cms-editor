import * as M from "materialize-css";


export const setupMaterialize = () => {
    M.Tabs.init(document.getElementById('add_parts_tabs'), {});
    M.FormSelect.init(document.querySelectorAll('select'), {});
};
