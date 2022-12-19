'use strict';

class UserModal extends Modal {
    constructor() {
        super({
            name: '.name',
            email: '.email',
            phone_number: '.phone_number',
            dob: '.dob',
            id: '.id'
        }, '.user-modal');

        this.setEventListeners();
    }

    setEventListeners() {
        document.querySelector('.membership-table').addEventListener('click', (e) => this.setModal(e.target, '.account-link'));
        document.querySelector('.search-bar-modal__output').addEventListener('click', (e) => {
            if (e.target.classList.contains('account-link')) {
                this.setModal(e.target, '.search-modal__row');
            }
        });

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());
    }

    setModal(element, parentSelector) {
        const user = this.getCurrentUser(element, parentSelector);
        this.elements.name.textContent = user.name;
        this.elements.email.textContent = user.email;
        this.elements.phone_number.textContent = user.phone_number;
        this.elements.dob.textContent = user.dob;
        this.openModal();
    }
}
