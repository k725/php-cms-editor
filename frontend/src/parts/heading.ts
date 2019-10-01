/**
 * Create the following Element
 *
 * <div class="row parts-header">
 *   <h5>{ headingValue }</h5>
 *   <div class="divider"></div>
 * </div>
 *
 * @param headingValue
 */
export const createHeadingParts = (headingValue: string): HTMLDivElement => {
    const divider = document.createElement('div');
    divider.classList.add('divider');

    const text = document.createElement('h5');
    text.textContent = headingValue;

    const partsHeader = document.createElement('div');
    partsHeader.classList.add('row', 'parts-header');
    partsHeader.appendChild(text);
    partsHeader.appendChild(divider);

    return partsHeader;
};