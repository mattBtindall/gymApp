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
        this.elements.tabs = {
            menu: {
                items: document.querySelectorAll('.user-modal__menu-item'),
                activity: document.querySelectorAll('.user-modal__menu-item')[0],
                membership: document.querySelectorAll('.user-modal__menu-item')[1],
                addMembership: document.querySelectorAll('.user-modal__menu-item')[2]
            },
            content: {
                items: document.querySelectorAll('.user-modal__item')
            }
        }

        this.setEventListeners();
        this.getModalStatus()
            .then(modalStatus => {
                if (modalStatus.open) {
                   this.openModalOnLoad(modalStatus.user_id, modalStatus.selected);
                }
            })
    }

    setEventListeners() {
        const membershipTable = document.querySelector('.membership-table');
        const searchOutput = document.querySelector('.search-bar-modal__output');
        const userModalMenuBar = document.querySelector('.user-modal__menu-bar');

        if (membershipTable)
            membershipTable.addEventListener('click', (e) => this.openModalOnClick(e.target, '.account-link', userData.get().members));

        if (searchOutput) {
            searchOutput.addEventListener('click', (e) => {
                if (e.target.classList.contains('account-link')) {
                    modals.search.closeModal();
                    this.openModalOnClick(e.target, '.search-modal__row', userData.get().allUsers);
                }
            });
        }

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());

        if (userModalMenuBar){ // only performs this for admin
            this.setAddMembershipTab();
            userModalMenuBar.addEventListener('click', (e) => {
                if (e.target.classList.contains('user-modal__menu-item')) {
                    this.setTabs(e.target);
                }
            });
        }
    }

    setTabs(menuItem) {
        this.elements.tabs.menu.items.forEach(menuItem => menuItem.classList.remove('active'));
        menuItem.classList.add('active');
        this.elements.tabs.content.items.forEach(item => item.classList.remove('active'));
        document.querySelector(`.${menuItem.dataset.contentClassName}`).classList.add('active');
    }

    setAddMembershipTab() {
        this.setTerms();
        this.elements.addMembershipTab = {};
        this.elements.addMembershipTab.termDropDown = document.getElementsByName('term')[0];
        this.elements.addMembershipTab.cost = document.querySelector('.add-membership input.cost');
        const expiryDate = document.querySelector('.expiry-date');

        // Show & hide expiry date input for custom membership
        this.elements.addMembershipTab.termDropDown.addEventListener('click',(e) => {
            if (e.target.value === 'custom') {
                expiryDate.classList.add('active');
                this.elements.addMembershipTab.cost.value = '';
                // this.elements.addMembershipTab.cost.disabled = false;
            } else {
                expiryDate.classList.remove('active');
                this.elements.addMembershipTab.cost.value = e.target.dataset.cost;
                // this.elements.addMembershipTab.cost.disabled = true;
            }
        });
    }

    openModalOnLoad(currentUserId, selected) {
        const user = this.getUserById(currentUserId, userData.get().allUsers);
        // tries to set the selected drop down here
        Array.from(this.elements.addMembershipTab.termDropDown.children).forEach(option => {
            console.log(option.text);
        });
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
        this.setTabs(this.elements.tabs.menu.activity);
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
        this.elements.id.value = user.user_id;
    }

    getModalStatus() {
        // sees whether to open the modal or not from php
        const url = getPhpMethodUrl('/userModal/getStatus');
        return getData(url);
    }

    getTerms() {
        const url = getPhpMethodUrl('/terms/getTerms');
        return getData(url);
    }

    setTerms() {
        const setTerm = (term) => {
            const optionElement = document.createElement('option');
            optionElement.innerText = term['display_name'];
            optionElement.value = term['term_multiplier'] + ' ' + term['term'];
            optionElement.dataset.termId = term.id;
            optionElement.dataset.cost = term.cost;
            this.elements.addMembershipTab.termDropDown.appendChild(optionElement);
        }

        this.getTerms()
            .then((terms) => {
                if (terms.length === 0) {
                    return;
                }

                terms.forEach(term => setTerm(term));
            });
    }
}
