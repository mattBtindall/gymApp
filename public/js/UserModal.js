import { Modal } from './Modal.js';
import { modals, userData, getUserData } from './utils.js';

export class UserModal extends Modal {
    constructor(open, userId) { // both params are used to open the modal on load with correct user
        super({
            name: '.name',
            email: '.email',
            phone_number: '.phone_number',
            dob: '.dob',
            id: '.id'
        }, '.user-modal');

        // this.init(open, userId);
    }

    init(open, userId) {
        this.setEventListeners();

        if (!open) {
            return;
        }

        // if (!userData.length) {
            getUserData(window.location.href + '/getMembersData', () => this.openModalOnLoad(userId));
        // }
    }

    setEventListeners() {
        const membershipTable = document.querySelector('.membership-table');
        const searchOutput = document.querySelector('.search-bar-modal__output');

        if (membershipTable) membershipTable.addEventListener('click', (e) => this.openModalOnClick(e.target, '.account-link'));
        if (searchOutput) {
            searchOutput.addEventListener('click', (e) => {
                if (e.target.classList.contains('account-link')) {
                    this.openModalOnClick(e.target, '.search-modal__row');
                }
            });
        }

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());
    }

    openModalOnLoad(currentUserId) {
        const user = this.getUserById(currentUserId);
        this.setModal(user);
        this.openModal(user);
    }

    openModalOnClick(element, parentSelector) {
        const user = this.getCurrentUser(element, parentSelector);
        this.setModal(user);
        this.openModal(user);
    }

    closeModal() {
        if (!this.open) {
            return;
        }

        this.resetModalInputs();
        modals.search.closeModal();
        super.closeModal();
    }

    resetModalInputs() {
        const inputs = [
            document.querySelector('.user-modal__content .term'),
            document.querySelector('.user-modal__content .start_date'),
            document.querySelector('.user-modal__content .expiry_date')
        ]

        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            input.value = input.dataset.initialValue;
        });
    }

    setModal(user) {
        this.elements.name.textContent = user.name;
        this.elements.email.textContent = user.email;
        this.elements.phone_number.textContent = user.phone_number;
        this.elements.dob.textContent = user.dob;
        this.elements.id.value = user.id;
    }
}
