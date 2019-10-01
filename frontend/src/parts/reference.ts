/**
 * Create the following Element
 *
 * <div class="row parts-reference">
 *   <div class="card-panel blue-grey lighten-5">
 *     <p><a href="{ linkValue }">{ titleValue }</a></p>
 *     <p>{ descriptionValue }</p>
 *   </div>
 * </div>
 *
 * @param titleValue
 * @param descriptionValue
 * @param linkValue
 */
export const createReferenceParts = (
    titleValue: string,
    descriptionValue: string,
    linkValue: string
): HTMLDivElement => {
    const titleLink = document.createElement('a');
    titleLink.textContent = titleValue;
    titleLink.setAttribute('href', linkValue);

    const title = document.createElement('p');
    title.appendChild(titleLink);

    const description = document.createElement('p');
    description.textContent = descriptionValue;

    const cardPanel = document.createElement('div');
    cardPanel.classList.add('card-panel', 'blue-grey', 'lighten-5');
    cardPanel.appendChild(title);
    cardPanel.appendChild(description);

    const partsReference = document.createElement('div');
    partsReference.classList.add('row', 'parts-reference');
    partsReference.appendChild(cardPanel);

    return partsReference;
};