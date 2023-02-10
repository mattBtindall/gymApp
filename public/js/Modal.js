import { getPhpMethodUrl, getData } from './utils.js';

export class Modal {
    constructor(spec, parentSelector) {
        this.elements = {};
        this.open = false;

        this.setElements(spec, parentSelector);
    }

    setElements(elements, parentSelector = '') {
        this.elements.modal = document.querySelector(parentSelector);
        for (const key in elements) {
            this.elements[key] = document.querySelector(parentSelector + ' ' + elements[key]);
        }
    }

    openModal() {
        this.elements.modal.classList.add('active');
        document.body.classList.add('overlay-active');
        this.open = true;
    }

    closeModal() {
        this.elements.modal.classList.remove('active');
        document.body.classList.remove('overlay-active');
        this.open = false;
    }

    getUserById(id, users) {
        return users.filter(user => user['id'] == id)[0];
    }

    getCurrentUser(searchElement, parentSelector, data) {
        const currentUserId = searchElement.closest(parentSelector).querySelector('.id').textContent;
        return this.getUserById(currentUserId, data);
    }

    setHref(element, id) {
        const href = element.getAttribute('href');
        element.setAttribute('href', href + id);
    }

    logMember(id) {
        const url = getPhpMethodUrl(`/activitys/logUser/${id}`);
        return getData(url);
    }
}
