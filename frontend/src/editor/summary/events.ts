import {throttle} from "lodash";

export const setupSummaryEvents = () => {
    const updateSummary = throttle(() => {
        // document.getElementById('title').value
    }, 1000);

    document.getElementById('title').addEventListener('keydown', updateSummary, false);
    document.getElementById('title').addEventListener('focusout', updateSummary, false);
    document.getElementById('description').addEventListener('keydown', updateSummary, false);
    document.getElementById('description').addEventListener('focusout', updateSummary, false);
};
