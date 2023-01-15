import { modals } from "./utils.js";

export function setImgUpload() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');

    // Toggle btn for image upload in profile
    const imgTypes = ['jpg', 'jpeg', 'png'];
    fileInput.addEventListener('change', () => {
        if (!fileInput.files) {
            return;
        }

        for (const imgType of imgTypes) {
            if (fileInput.files[0].type.includes(imgType)) {
                uploadbtn.disabled = false;
                return;
            }
        }
    });
}

export function setMembershipTab() {
    const term = document.getElementsByName('term')[0];
    const expiryDate = document.querySelector('.expiry-date');

    // Show & hide expiry date input
    term.addEventListener('click', function(e) {
        if (e.target.value === 'custom') {
            expiryDate.classList.add('active');
        } else {
            expiryDate.classList.remove('active');
        }
    });
}

export function setTermsEditTable() {
    const elements = {
        table: document.querySelector('.terms-table'),
        displayNameInput: document.querySelectorAll('.terms-edit__display-name'),
        term: document.querySelectorAll('.terms-edit__term'),
        dropDown: document.querySelectorAll('.terms-edit__drop-down'),
        costInput: document.querySelectorAll('.terms-edit__cost'),
        submitBtn: document.querySelectorAll('.terms-edit__submit'),
        editBtn: document.querySelectorAll('.terms-edit__edit')
    };

    const tableClickListener = (e) => {
        e.preventDefault();
        if (!e.target.classList.contains('terms-edit__edit')) {
            return;
        }
        
        // disable unclicked inputs
        const currentTermNumber = e.target.dataset.termNumber;
        for (let i = 0; i < elements.editBtn.length; i++) {
            if (i === +currentTermNumber) continue; 
            elements.displayNameInput[i].disabled = true;
            elements.submitBtn[i].disabled = true;
            elements.costInput[i].disabled = true;
            elements.dropDown[i].classList.remove('active');
            elements.term[i].classList.add('active');
        }
        
        // toggle current inputs
        if (elements.submitBtn[currentTermNumber].disabled) {
            elements.displayNameInput[currentTermNumber].disabled = false;
            elements.submitBtn[currentTermNumber].disabled = false;
            elements.costInput[currentTermNumber].disabled = false;
        } else {
            elements.displayNameInput[currentTermNumber].disabled = true;
            elements.submitBtn[currentTermNumber].disabled = true;
            elements.costInput[currentTermNumber].disabled = true;
        }

        elements.dropDown[currentTermNumber].classList.toggle('active');
        elements.term[currentTermNumber].classList.toggle('active');
    }

    elements.table.addEventListener('click', tableClickListener);
}

export function setBodyClick() {
    document.body.addEventListener('click', e => {
        if (e.target === document.body) {
            for (const key in modals) {
                if (modals[key].open) {
                    modals[key].closeModal();
                }
            }
        }
    });
}