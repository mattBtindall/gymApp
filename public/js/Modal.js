import { userData } from "./utils.js";

export class Modal {
    constructor(spec, parentSelector) {
        this.elements = {};
        this.open = false;

        this.setElements(spec, parentSelector);
    }

    setElements(elements, parentSelector = '') {
        this.elements.modal = document.querySelector(parentSelector);
        for (const key in elements) {
            this.elements[key] = document.querySelector(parentSelector + ' ' + elements[key]);
        }
    }

    openModal() {
        this.elements.modal.classList.add('active');
        document.body.classList.add('overlay-active');
        this.open = true;
    }

    closeModal() {
        this.elements.modal.classList.remove('active');
        document.body.classList.remove('overlay-active');
        this.open = false;
    }

    getUserById(id) {
        return userData.filter(user => user.id == id)[0];
    }

    getCurrentUser(searchElement, parentSelector) {
        const currentUserId = searchElement.closest(parentSelector).querySelector('.id').textContent;
        return this.getUserById(currentUserId);
    }
}
