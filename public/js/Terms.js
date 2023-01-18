import { getPhpMethodUrl, getData } from "./utils.js";

export class Terms {
    constructor() {
        this.elements = {
            inputs: {},
            table: document.querySelector('.terms-table'),
            rows: document.querySelector('.terms-table tr'),
            addBtn: document.querySelector('.add-term')
        };

        // sets inputs so this.elements[rowId] = { rowElements }
        const idElements = document.getElementsByName('term_id');
        idElements.forEach(idElement => {
            const parentNode = idElement.parentNode;
            const id = idElement.value;
            this.elements.inputs[id] = {
                displayNameInput: parentNode.querySelector('.terms-edit__display-name'),
                term: parentNode.querySelector('.terms-edit__term'),
                dropDown: parentNode.querySelector('.terms-edit__drop-down'),
                costInput: parentNode.querySelector('.terms-edit__cost'),
                submitBtn: parentNode.querySelector('.terms-edit__submit'),
                editBtn: parentNode.querySelector('.terms-edit__edit'),
            }
        });

        this.setEventListeners();
        this.openOnLoad();
    }

    setEventListeners() {
        this.elements.table.addEventListener('click', (e) => this.tableClick(e));
        this.elements.addBtn.addEventListener('click', () => this.addBtnClick());
    }

    tableClick(e) {
        if (!e.target.classList.contains('terms-edit__edit')) {
            return;
        }
        e.preventDefault();

        const rowId = e.target.closest('tr').querySelector('input[name="term_id"]').value;
        this.disableAllRows(rowId);
        this.toggleRow(this.elements.inputs[rowId]);
    }

    addBtnClick() {
        const date = new Date(Date.now()).toLocaleString();
        const formattedDate = date.slice(0,date.lastIndexOf(','));
        const emptyTemplate = document.getElementById('new-term');
        const emptyRow = document.importNode(emptyTemplate.content, true);
        emptyRow.querySelector('.date-created').textContent = formattedDate;
        this.elements.table.appendChild(emptyRow);
    }

    disableRow(elements) {
        elements.displayNameInput.disabled = true;
        elements.submitBtn.disabled = true;
        elements.costInput.disabled = true;
        elements.dropDown.classList.remove('active');
        elements.term.classList.add('active');
    }

    disableAllRows(rowIdToExclude) {
        for (let key in this.elements.inputs) {
            if (rowIdToExclude == key) continue;
            this.disableRow(this.elements.inputs[key]);
        }
    }

    enableRow(elements) {
        elements.displayNameInput.disabled = false;
        elements.submitBtn.disabled = false;
        elements.costInput.disabled = false;
        elements.dropDown.classList.add('active');
        elements.term.classList.remove('active');
    }

    toggleRow(elements) {
        if (elements.displayNameInput.disabled) {
            this.enableRow(elements);
        } else {
            this.disableRow(elements);
        }
    }

    getErrorState() {
        const url = getPhpMethodUrl('/terms/getErrorStatus');
        return getData(url);
    }

    openOnLoad() {
        this.getErrorState()
            .then(termId => {
                // check to see if the element exists, may have been deleted by user
                if (termId && this.elements.inputs.hasOwnProperty(termId)) {
                    this.enableRow(this.elements.inputs[termId])
                }
            });
    }
}
