import { Modal } from './Modal.js';
import { userData, getData, getPhpMethodUrl, modals, getMembershipStatusClasses } from './utils.js';

export class SearchModal extends Modal {
    constructor() {
        super({
            output: '.search-bar-modal__output',
        }, '.search-bar-modal');

        this.elements.searchBar = document.querySelector('.search-bar');
        this.setEventListeners();
    }

    setEventListeners() {
        const searchOutput = document.querySelector('.search-bar-modal__output');
        this.elements.searchBar.addEventListener('focus', (e) => this.openModal());

        this.elements.searchBar.addEventListener('keyup', e => {
            if (!e.target.value.length) {
                this.setEmptyModalMessage('Type a name in the search bar');
                return;
            }

            const url = getPhpMethodUrl("/Users/searchDb/", e.target.value);
            getData(url)
                .then(data => {
                    this.setModal(data)
                });
        });

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

    setModal(data) {
        if (data === '{}') {
            this.setEmptyModalMessage('No user found with this name');
            return;
        }

        this.elements.output.innerHTML = "";
        const createRow = (rowData) => {
            // user details
            const rowTemplate = document.getElementById('row');
            const rowBody = document.importNode(rowTemplate.content, true);
            rowBody.querySelector('.row-img').src = rowData['img_url'];
            rowBody.querySelector('.name').textContent = rowData['name'];
            rowBody.querySelector('.email').textContent = rowData['email'];
            rowBody.querySelector('.phone_number').textContent = rowData['phone_number'];
            rowBody.querySelector('.id').textContent = rowData['id'];

            const logBtn = rowBody.querySelector('.log-btn');
            logBtn.addEventListener('click', () => {
                this.logMember(rowData['id'])
                    .then(activity => {
                        if (activity !== '{}' && this.activity) this.activity.addActivityElement(activity);
                    });
            })

            // set membership section
            let membershipStatus = '';
            switch (rowData.status) {
                case 'active' :
                    membershipStatus = rowData.term_display_name;
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
            rowBody.querySelector('.expiry-date').textContent = rowData.status ? rowData.expiry_date : 'No membership';
            const htmlClasses = getMembershipStatusClasses(rowData.status, ['text']);
            rowBody.querySelector('.membership-details').classList.add(...htmlClasses);

            this.elements.output.appendChild(rowBody);
        }

        data.forEach(rowData => createRow(rowData));
    }
}
