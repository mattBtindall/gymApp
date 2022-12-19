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

    setModal(elements, user) {
        for (const key in elements) {
            elements[key].textContent = user[key];
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
}

// const userMdodal = new Modal({
//     modal: '.user-modal',
//     name: '.name',
//     email: '.email',
//     phone_number: '.phone_number',
//     dob: '.dob',
//     id: '.id'
// }, '.user-modal');

