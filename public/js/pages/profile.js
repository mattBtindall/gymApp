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
