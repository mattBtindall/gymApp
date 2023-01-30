import { Modal } from './Modal.js';
import { modals, userData, getPhpMethodUrl, getData, sendAjax, isAdmin } from './utils.js';

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

        isAdmin()
            .then(admin => {
                if (admin.isAdmin) {
                    this.setAddMembershipTab();
                }
            });

        this.setEventListeners();
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
                    this.openModalOnClick(e.target, '.search-modal__row', userData.get().allUsers);
                }
            });
        }

        document.querySelector('.exit-modal-container i').addEventListener('click', () => this.closeModal());

        if (userModalMenuBar){
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
        this.elements.addMembershipTab = {
            termDropDown: document.querySelector('.user-modal__content .term'),
            startDate: document.querySelector('.user-modal__content .start-date'),
            cost: document.querySelector('.user-modal__content .cost'),
            expiryDate: document.querySelector('.user-modal__content .expiry-date')
        };

        this.getTerms()
            .then(terms => this.setTerms(terms))
            .then(() => this.getModalStatus())
            .then(modalStatus => this.openModalOnLoad(modalStatus.open, modalStatus.user_id, modalStatus.selected))
            .catch(e => console.log(e));

        const addMembershipDropDownClick = (e) => {
            const dropDownOption = e.target.querySelector(`option[value="${e.target.value}"]`);
            const expiryDateParent = this.elements.addMembershipTab.expiryDate.closest('.expiry-date-container');

            // Show & hide expiry date input for custom membership
            if (e.target.value === 'custom') {
                expiryDateParent.classList.add('active');
                this.elements.addMembershipTab.cost.value = '';
                this.elements.addMembershipTab.cost.readOnly = false;
            } else {
                expiryDateParent.classList.remove('active');
                this.elements.addMembershipTab.cost.value = dropDownOption.dataset.cost;
                this.elements.addMembershipTab.cost.readOnly= true;
            }
        }

        this.elements.addMembershipTab.termDropDown.addEventListener('change', addMembershipDropDownClick);
    }

    setModal(user) {
        this.elements.name.textContent = user.name;
        this.elements.email.textContent = user.email;
        this.elements.phone_number.textContent = user.phone_number;
        this.elements.dob.textContent = user.dob;
        this.elements.id.value = user.user_id;
    }

    setTerm(term) {
        const optionElement = document.createElement('option');
        optionElement.innerText = term['display_name'];
        optionElement.value = term.id;
        optionElement.dataset.cost = term.cost;
        this.elements.addMembershipTab.termDropDown.appendChild(optionElement);
    }

    setTerms(terms) {
        return new Promise((res, rej) => {
            if (terms.length === 0) {
                rej('No terms');
            } else {
                for (const key in terms) {
                    this.setTerm(terms[key]);
                }
                res();
            }
        })
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

    openModalOnLoad(openModal, currentUserId, selected) {
        if (!openModal) return;
        const user = this.getUserById(currentUserId, userData.get().allUsers);
        Array.from(this.elements.addMembershipTab.termDropDown.children).forEach(option => {
            if (option.value == selected) {
                option.selected = true;
            }
        });

        if (selected === 'custom') {
            this.elements.addMembershipTab.cost.readOnly = false;
        }
        this.setTabs(this.elements.tabs.menu.addMembership);
        this.setModal(user);
        this.openModal(user);
    }

    openModalOnClick(element, parentSelector, data) {
        modals.search.closeModal();
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
        this.resetAddMembershipTab();
        this.setTabs(this.elements.tabs.menu.activity);
        modals.search.closeModal();
        super.closeModal();
    }

    resetAddMembershipTab() {
        const { addMembershipTab: inputs } = this.elements;
        inputs.expiryDate.classList.remove('active');
        for (const key in inputs) {
            inputs[key].classList.remove('is-invalid');
            inputs[key].value = inputs[key].dataset.initialValue;
        }
    }
}
