import { Controller } from '@hotwired/stimulus';

/*
 * Usage in Twig:
 * <div data-controller="form-collection"
 * data-form-collection-index-value="{{ form.invoiceItems|length > 0 ? form.invoiceItems|last.vars.name + 1 : 0 }}"
 * data-form-collection-prototype-value="{{ form.invoiceItems.vars.prototype|e('html_attr') }}">
 * ...
 * </div>
 */
export default class extends Controller {
    static targets = ["collectionContainer"];

    static values = {
        index: Number,
        prototype: String,
    };

    addCollectionElement(event) {
        // 1. Create a new DOM element from the prototype string
        // The __name__ placeholder is replaced by the current index
        const item = this.prototypeValue.replace(/__name__/g, this.indexValue);
        
        // 2. Insert it into the container (tbody)
        this.collectionContainerTarget.insertAdjacentHTML('beforeend', item);
        
        // 3. Increment index so the next item has a unique ID
        this.indexValue++;
    }

    removeCollectionElement(event) {
        event.preventDefault();
        
        // Find the closest table row (tr) and remove it
        const item = event.target.closest('tr');
        item.remove();
    }
}