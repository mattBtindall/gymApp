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
        // can be either user_id or id here as the data comes from different places
        return users.filter(user => user['user_id'] == id || user['id'] == id)[0];
    }

    getCurrentUser(searchElement, parentSelector, data) {
        const currentUserId = searchElement.closest(parentSelector).querySelector('.id').textContent;
        return this.getUserById(currentUserId, data);
    }
}
