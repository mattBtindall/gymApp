'use strict';

class Modal {
    constructor(spec, parentSelector) {
        this.elements = {};
        this.modalOpen = false;

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
        this.modalOpen = true;
    }

    closeModal() {
        this.elements.modal.classList.remove('active');
        document.body.classList.remove('overlay-active');
        this.modalOpen = false;
    }

    getCurrentUser(searchElement, parentSelector) {
        const currentUserId = searchElement.closest(parentSelector).querySelector('.id').textContent;
        return userData.filter(user => user.id == currentUserId)[0];
    }
}
