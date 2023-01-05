import { Modal } from './Modal.js';
import { modals, userData, getPhpMethodUrl, getData, sendAjax } from './utils.js';

export class UserModal extends Modal {
    constructor() {
        super({
            name: '.name',
            email: '.email',
            phone_number: '.phone_number',
            dob: '.dob',
            id: '.id'
        }, '.user-modal');

        this.init();
    }

    init() {
        this.setEventListeners();
        this.getModalStatus()
            .then(modalStatus => {
                if (modalStatus.open) {
                   this.openModalOnLoad(modalStatus.user_id);
                }
            })
    }

    setEventListeners() {
        const membershipTable = document.querySelector('.membership-table');
        const searchOutput = document.querySelector('.search-bar-modal__output');

        if (membershipTable) membershipTable.addEventListener('click', (e) => this.openModalOnClick(e.target, '.account-link', userData.get().members));
        if (searchOutput) {
            searchOutput.addEventListener('click', (e) => {
                if (e.target.classList.contains('account-link')) {
                    modals.search.closeModal();
                    this.openModalOnClick(e.target, '.search-modal__row', userData.get().allUsers);
                }
            });
        }

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());
    }

    openModalOnLoad(currentUserId) {
        const user = this.getUserById(currentUserId, userData.get().allUsers);
        console.log(user);
        this.setModal(user);
        this.openModal(user);
    }

    openModalOnClick(element, parentSelector, data) {
        const user = this.getCurrentUser(element, parentSelector, data);
        this.setModal(user);
        this.openModal(user);
    }

    closeModal() {
        if (!this.open) {
            return;
        }

        const url = getPhpMethodUrl('/userModal/disable');
        sendAjax(url); // remove modal errors so it doesn't reopen
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

    getModalStatus() {
        // sees whether to open the modal or not from php
        const url = getPhpMethodUrl('/userModal/getStatus');
        return getData(url);
    }
}
