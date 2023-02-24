import { Modal } from './Modal.js';
import { userData, getData, getPhpMethodUrl, modals, getMembershipStatusClasses } from './utils.js';

export class SearchModal extends Modal {
    constructor() {
        super(
            { output: '.search-bar-modal__output'},
            '.search-bar-modal'
        );

        this.elements.searchBar = document.querySelector('.search-bar');
        this.setEventListeners();
    }

    setEventListeners() {
        const searchOutput = document.querySelector('.search-bar-modal__output');
        this.elements.searchBar.addEventListener('focus', (e) => this.openModal());
        this.elements.searchBar.addEventListener('keyup', e => this.keyUpEventListener(e));

        if (searchOutput) {
            searchOutput.addEventListener('click', (e) => {
                if (e.target.classList.contains('account-link')) {
                    modals.user.openModalOnClick(e.target, '.search-modal__row', userData.get());
                }

                if (e.target.classList.contains('add-membership', 'btn')) {
                    modals.user.openModalOnClick(e.target, '.search-modal__row', userData.get(), 'addMembership');
                }
            });
        }
    }

    keyUpEventListener(e) {
        if (!e.target.value.length) {
            this.setEmptyModalMessage('Type a name in the search bar');
            return;
        }

        const url = getPhpMethodUrl("/Users/searchDb/", e.target.value);
        getData(url)
            .then(data => {
                this.setModal(data)
            });
    }

    setEmptyModalMessage(message) {
        this.elements.output.innerHTML = "";
        const emptySearchTemplate = document.getElementById('empty-searchbar-msg');
        const emptySearchContainer = document.importNode(emptySearchTemplate.content, true);
        emptySearchContainer.querySelector('span').textContent = message;
        this.elements.output.appendChild(emptySearchContainer);
    }

    closeModal() {
        if (!this.open) {
            return;
        }

        this.elements.searchBar.value = "";
        this.setEmptyModalMessage('Type a name in the search bar');
        super.closeModal();
    }

    createRow(userData) {
        const rowTemplate = document.getElementById('row');
        const rowBody = document.importNode(rowTemplate.content, true);
        rowBody.querySelector('.row-img').src = userData['img_url'];
        rowBody.querySelector('.name').textContent = userData['name'];
        rowBody.querySelector('.email').textContent = userData['email'];
        rowBody.querySelector('.phone_number').textContent = userData['phone_number'];
        rowBody.querySelector('.id').textContent = userData['id'];

        const logBtn = rowBody.querySelector('.log-btn');
        logBtn.addEventListener('click', () => {
            this.logMember(userData['id'])
                .then(activity => {
                    if (activity !== '{}' && this.activity) this.activity.addActivityElement(activity);
                });
        })

        // set membership section
        let membershipStatus = '';
        switch (userData.status) {
            case 'active' :
                membershipStatus = userData.term_display_name;
                break;
            case 'expired' :
                membershipStatus = 'Expired'
                logBtn.classList.add('disabled');
                break;
            case 'future' :
                membershipStatus = 'Starts'
                logBtn.classList.add('disabled');
                break;
            default :
                membershipStatus = '';
                logBtn.classList.add('disabled');
        }
        rowBody.querySelector('.term-display-name').textContent = membershipStatus;
        rowBody.querySelector('.expiry-date').textContent = userData.status ? userData.expiry_date : 'No membership';
        const htmlClasses = getMembershipStatusClasses(userData.status, ['text']);
        rowBody.querySelector('.membership-details').classList.add(...htmlClasses);

        this.elements.output.appendChild(rowBody);
    }

    setModal(data) {
        if (data === '{}') {
            this.setEmptyModalMessage('No user found with this name');
            return;
        }
        this.elements.output.innerHTML = "";
        data.forEach(userData => this.createRow(userData));
    }
}
