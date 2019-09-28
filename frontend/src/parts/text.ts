/**
 * Create the following Element
 *
 * <div class="row parts-text">
 *   <p>{ textValue }</p>
 * </div>
 *
 * @param id
 * @param textValue
 */
export const createTextParts = (id: number, textValue: string): HTMLDivElement => {
    // @todo \n to <br>
    const text = document.createElement('p');
    text.textContent = textValue;

    const partsText = document.createElement('div');
    partsText.classList.add('row', 'parts-text');
    partsText.appendChild(text);
    partsText.dataset.id = id.toString();

    return partsText;
};