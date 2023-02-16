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

export function setHtmlDateDefault() {
    const dateInput = document.querySelector('.activity-date');
    const form = document.querySelector('.activity-form');

    // not the best code called new Date twice but js date functions are a mess
    // either gets the date from the url or sets that date to today
    const regex = /'\d{4}-\d{2}-\d{2}'$/; // 'yyyy-mm-dd'
    let date = window.location.href.match(regex);
    if (date) {
        date = date[0].replace(/'/g, "");
        const [year, month, day] = date.split("-");
        date = new Date(year, month - 1, day);
    } else {
        date = new Date();
    } 
    dateInput.valueAsDate = date;

    dateInput.addEventListener('change', (e) => {
        // append date to form action - needs formatting with single quotes for SQL query
        form.action += "'" + e.target.value + "'";
        form.submit();
    });
}