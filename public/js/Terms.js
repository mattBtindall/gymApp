export class Terms {
    constructor() {
        this.elements = {
            table: document.querySelector('.terms-table'),
            displayNameInput: document.querySelectorAll('.terms-edit__display-name'),
            term: document.querySelectorAll('.terms-edit__term'),
            dropDown: document.querySelectorAll('.terms-edit__drop-down'),
            costInput: document.querySelectorAll('.terms-edit__cost'),
            submitBtn: document.querySelectorAll('.terms-edit__submit'),
            editBtn: document.querySelectorAll('.terms-edit__edit')
        };

        this.setEventListeners();
    }

    setEventListeners() {
        this.elements.table.addEventListener('click', (e) => this.tableClick(e));
    }

    tableClick(e) {
        if (!e.target.classList.contains('terms-edit__edit')) {
            return;
        }
        e.preventDefault();

        const currentTermNumber = parseInt(e.target.dataset.termNumber);
        this.disableAllInputs(currentTermNumber);
        this.enableCurrentInputs(currentTermNumber);
    }

    disableAllInputs(termToExclude) {
        const numberOfTerms = this.elements.table.querySelectorAll('tr').length - 1; // - 1 for the headings
        for (let i = 0; i < numberOfTerms; i++) {
            if (i === termToExclude) continue;
            this.elements.displayNameInput[i].disabled = true;
            this.elements.submitBtn[i].disabled = true;
            this.elements.costInput[i].disabled = true;
            this.elements.dropDown[i].classList.remove('active');
            this.elements.term[i].classList.add('active');
        }
    }

    enableCurrentInputs(currentTermNumber) {
        if (this.elements.submitBtn[currentTermNumber].disabled) {
            this.elements.displayNameInput[currentTermNumber].disabled = false;
            this.elements.submitBtn[currentTermNumber].disabled = false;
            this.elements.costInput[currentTermNumber].disabled = false;
        } else {
            this.elements.displayNameInput[currentTermNumber].disabled = true;
            this.elements.submitBtn[currentTermNumber].disabled = true;
            this.elements.costInput[currentTermNumber].disabled = true;
        }

        this.elements.dropDown[currentTermNumber].classList.toggle('active');
        this.elements.term[currentTermNumber].classList.toggle('active');
    }
}
