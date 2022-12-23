import { Modal } from './Modal.js';
import { getData, getPhpMethodUrl } from './utils.js';

export class SearchModal extends Modal {
    constructor() {
        super({
            output: '.search-bar-modal__output',
        }, '.search-bar-modal');

        this.elements.searchBar = document.querySelector('.search-bar');
        this.setEventListeners();
    }

    setEventListeners() {
        this.elements.searchBar.addEventListener('focus', (e) => this.openModal());

        this.elements.searchBar.addEventListener('keyup', e => {
            if (!e.target.value.length) {
                this.setEmptyModalMessage('Type a name in the search bar');
                return;
            }

            const url = getPhpMethodUrl("Users/searchDb/", e.target.value);
            getData(url)
                .then(data => this.setModal(data));
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

    setModal(data) {
        if (data === '{}') {
            this.setEmptyModalMessage('No user found with this name');
            return;
        }

        this.elements.output.innerHTML = "";
        const createRow = (rowData) => {
            const rowTemplate = document.getElementById('row');
            const rowBody = document.importNode(rowTemplate.content, true);
            const nameOutput = rowBody.querySelector('.name');
            rowBody.querySelector('.row-img').src = rowData['img_url'];
            nameOutput.textContent = rowData['name'];
            rowBody.querySelector('.email').textContent = rowData['email'];
            rowBody.querySelector('.phone_number').textContent = rowData['phone_number'];
            rowBody.querySelector('.id').textContent = rowData['id'];
            this.elements.output.appendChild(rowBody);
        }

        data.forEach(rowData => createRow(rowData));
    }
}
