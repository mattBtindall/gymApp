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

export function keyPress() {
    document.addEventListener('keydown', e => {
        if (e.code === 'Escape') {
            if (modals.search.open) {
                modals.search.closeModal();
            } else if (modals.user.open) {
                modals.user.closeModal();
            }
        }
    });
}
