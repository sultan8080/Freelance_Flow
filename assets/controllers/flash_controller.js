import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = {
        duration: { type: Number, default: 3000 }
    }

    connect() {
        setTimeout(() => {
            this.element.classList.add('opacity-0', 'translate-y-2')
            setTimeout(() => this.element.remove(), 300)
        }, this.durationValue)
    }
}
