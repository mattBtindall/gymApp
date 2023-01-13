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
    // if edit button
    // enable cost input and submit button
    // hide term and show select
    const elements = {
        term: document.querySelector('.terms-edit__term'),
        dropDown: document.querySelector('.terms-edit__drop-down'),
        costInput: document.querySelector('.terms-edit__cost'),
        submitBtn: document.querySelector('.terms-edit__submit'),
        editBtn: document.querySelector('.terms-edit__edit')
    }

    const toggleInput = (element) => {
        if (element.disabled) {
            element.disabled = false;
        } else {
            element.disabled = true;
        }
    }

    const editClick = (e) => {
        e.preventDefault();
        elements.dropDown.classList.toggle('active');
        elements.term.classList.toggle('active');
        toggleInput(elements.submitBtn);
        toggleInput(elements.costInput);
    };

    elements.editBtn.addEventListener('click', editClick);
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