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
        document.querySelector('.membership-table').addEventListener('click', () => this.openModal());
        document.querySelector('.search-bar-modal__output').addEventListener('click', (e) => {
            if (e.target.classList.contains('account-link')) {
                this.openModal();
            }
        });

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());
    }
}

window.onload = function() {
    const userModal = new UserModal();
}