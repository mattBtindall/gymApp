import { getPhpMethodUrl, getData } from "./utils.js";

export class Terms {
    constructor() {
        this.elements = {
            edit: {
                allInputs: {},
                rows: document.querySelector('.terms-table tr'),
            },
            add: {
                btn: document.querySelector('.add-term'),
                row: document.querySelector('.new-term'),
                deleteBtn: document.querySelector('.new-term__delete'),
                inputs: {
                    displayName: document.querySelector('.new-term__display-name'),
                    dropDown: document.querySelector('.new-term__term'),
                    cost: document.querySelector('.new-term__cost')
                }
            },
            table: document.querySelector('.terms-table')
        };

        // sets edit inputs so this.elements[rowId] = { rowElements }
        // const idElements = document.getElementsByName('term_id');
        const idElements = document.querySelectorAll('input[name="term_id"]');
        idElements.forEach(idElement => {
            const parentNode = idElement.parentNode;
            const id = idElement.value;
            this.elements.edit.allInputs[id] = {
                displayName: parentNode.querySelector('.terms-edit__display-name'),
                term: parentNode.querySelector('.terms-edit__term'),
                dropDown: parentNode.querySelector('.terms-edit__drop-down'),
                cost: parentNode.querySelector('.terms-edit__cost'),
                submitBtn: parentNode.querySelector('.terms-edit__submit'),
                editBtn: parentNode.querySelector('.terms-edit__edit'),
            }
        });

        this.setEventListeners();
        this.openOnLoad();
    }

    setEventListeners() {
        this.elements.table.addEventListener('click', (e) => this.tableClick(e));
        this.elements.add.btn.addEventListener('click', () => this.addBtnClick());
        this.elements.add.deleteBtn.addEventListener('click', () => this.addDeleteBtnClick());
        this.elements.add.inputs.dropDown.addEventListener('change', (e) => this.addNewTermDropDownChange(e));
    }

    /* ### Edit row functions ### */
    tableClick(e) {
        if (!e.target.classList.contains('terms-edit__edit')) {
            return;
        }
        e.preventDefault();

        const rowId = e.target.closest('tr').querySelector('input[name="term_id"]').value;
        this.disableAllRows(rowId);
        this.toggleRow(this.elements.edit.allInputs[rowId]);
    }

    disableRow(row) {
        row.displayName.disabled = true;
        row.submitBtn.disabled = true;
        row.cost.disabled = true;
        row.dropDown.classList.remove('active');
        row.term.classList.add('active');
    }

    disableAllRows(rowIdToExclude) {
        for (let key in this.elements.edit.allInputs) {
            if (rowIdToExclude == key) continue;
            this.disableRow(this.elements.edit.allInputs[key]);
        }
    }

    enableRow(row) {
        row.displayName.disabled = false;
        row.submitBtn.disabled = false;
        row.cost.disabled = false;
        row.dropDown.classList.add('active');
        row.term.classList.remove('active');
    }

    toggleRow(elements) {
        if (elements.displayName.disabled) {
            this.enableRow(elements);
        } else {
            this.disableRow(elements);
        }
    }

    getEditErrorState() {
        // error state for edit as inputs need enabling on load, if errors with add inputs are already enabled
        const url = getPhpMethodUrl('/terms/getErrorStatus');
        return getData(url);
    }

    openOnLoad() {
        this.getEditErrorState()
            .then(termId => {
                // check to see if the element exists, may have been deleted by user
                if (termId && this.elements.edit.allInputs.hasOwnProperty(termId)) {
                    this.enableRow(this.elements.edit.allInputs[termId])
                }
            });
    }

    /* ### Add row functions ### */
    addBtnClick() {
        this.elements.add.row.classList.add('active');
        this.elements.add.btn.disabled = true;
    }

    addDeleteBtnClick() {
        this.elements.add.inputs.displayName.value = '';
        this.elements.add.inputs.dropDown.value = 'please_select';
        this.elements.add.inputs.cost.value = '';
        this.elements.add.row.classList.remove('active');
        this.elements.add.btn.disabled = false;
    }

    addNewTermDropDownChange(e) {
        const dropDown = e.target;
        const selectedText = dropDown.options[dropDown.selectedIndex].text;
        let matches = 0;
        // see how many display names match the selected option
        for (let key in this.elements.edit.allInputs) {
            if (this.elements.edit.allInputs[key].displayName.value.includes(selectedText)) {
                matches++;
            }
        }
        matches = matches ? '(' + matches + ')' : '';

        if (selectedText !== 'Please select') {
            this.elements.add.inputs.displayName.value = `${selectedText} ${matches}`;
        } else {
            this.elements.add.inputs.displayName.value = '';
        }
    }
}
