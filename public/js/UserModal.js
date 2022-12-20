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
        document.querySelector('.membership-table').addEventListener('click', (e) => this.openModalClick(e.target, '.account-link'));
        document.querySelector('.search-bar-modal__output').addEventListener('click', (e) => {
            if (e.target.classList.contains('account-link')) {
                this.openModalClick(e.target, '.search-modal__row');
            }
        });

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());
    }

    openModalClick(element, parentSelector) {
        const user = this.getCurrentUser(element, parentSelector);
        this.setModal(user);
    }

    setModal(user) {
        this.elements.name.textContent = user.name;
        this.elements.email.textContent = user.email;
        this.elements.phone_number.textContent = user.phone_number;
        this.elements.dob.textContent = user.dob;
        this.elements.id.value = user.id;
        this.openModal();
    }
}
