'use strict';
window.onload = init();

function init() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');
    // document.body.classList.add('overlay-active');

    if (fileInput) {
        const imgTypes = ['jpg', 'jpeg', 'png'];

        fileInput.addEventListener('change', function() {
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
}
