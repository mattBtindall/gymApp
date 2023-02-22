import { modals, userData } from "./utils.js";

export class Activity {
    constructor(spec) {
        this.elements = {
            container: document.querySelector(spec.container),
            template: document.getElementById(spec.template),
            date: document.querySelector('.activity-date')
        }

        this.init();
    }

    init() {
        this.setHtmlDateDefault();
        this.setEventListeners();
    }

    setHtmlDateDefault() {
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
        this.elements.date.valueAsDate = date;

        this.elements.date.addEventListener('change', (e) => {
            // append date to form action - needs formatting with single quotes for SQL query
            form.action += "'" + e.target.value + "'";
            form.submit();
        });
    }

    addActivityElement() {

    }

    setEventListeners() {
        this.elements.container.addEventListener('click', (e) => {
            if (e.target.closest('.activity-container')) {
                modals.user.openModalOnClick(e.target, '.activity-container', userData.get());
            }
        });
    }
}
