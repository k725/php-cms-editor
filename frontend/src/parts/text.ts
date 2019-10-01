/**
 * Create the following Element
 *
 * <div class="row parts-text">
 *   <p>{ textValue }</p>
 * </div>
 *
 * @param textValue
 */
export const createTextParts = (textValue: string): HTMLDivElement => {
    // @todo \n to <br>
    const text = document.createElement('p');
    text.textContent = textValue;

    const partsText = document.createElement('div');
    partsText.classList.add('row', 'parts-text');
    partsText.appendChild(text);

    return partsText;
};