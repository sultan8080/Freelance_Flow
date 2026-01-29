import { Controller } from '@hotwired/stimulus';

/**
 * Manages Symfony Form Collections (prototype pattern).
 * * Capabilities:
 * - Adds new items based on the data-prototype attribute.
 * - Removes items and cleans up the DOM.
 * - Dispatches a 'collection-changed' event for other controllers (e.g., Invoice Summary).
 * * @example
 * <div data-controller="form-collection"
 * data-form-collection-index-value="0"
 * data-form-collection-prototype-value="...">
 * ...
 * </div>
 */
export default class extends Controller {
    static targets = ["collectionContainer"];

    static values = {
        index: Number,
        prototype: String,
    };

    /**
     * Adds a new row to the collection and updates the index.
     * Dispatches 'collection-changed' to notify listeners.
     */
    addCollectionElement(event) {
        const item = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.insertAdjacentHTML('beforeend', item);
        this.indexValue++;
        this.dispatch('collection-changed');
    }
    /**
     * 
     * Removes the parent row of the clicked button.
     * Dispatches 'collection-changed' to notify listeners.
     */

    removeCollectionElement(event) {
        event.preventDefault();
        const item = event.target.closest('tr');
        item.remove();
        this.dispatch('collection-changed');
    }
}