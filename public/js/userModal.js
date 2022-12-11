'use strict';
let modalOpen = false;

window.onload = function() {
    const userModalElements = {
        modal: document.querySelector('.user-modal'),
        name: document.querySelector('.user-modal .name'),
        email: document.querySelector('.user-modal .email'),
        phone_number: document.querySelector('.user-modal .phone_number'),
        dob: document.querySelector('.user-modal .dob')
    }
    const isDescendant = (child, parent) => parent.contains(child);

    const openUserModal = (e) => {
        if (e.target.classList.contains('account-link')) {
            closeSearchModal();
            setUserModal(e.target);
            modalOpen = true;
            document.body.classList.add('overlay-active');
            userModalElements.modal.classList.add('active');
        }
    };

    const closeUserModal = (e) => {
        modalOpen = false;
        userModalElements.modal.classList.remove('active');
        document.body.classList.remove('overlay-active');
    };

    const setUserModal = (searchElement) => {
        const currentUserId = searchElement.closest('.search-modal__row').querySelector('.id').textContent;
        const currentUser = userData.filter(user => user.id == currentUserId)[0];

        userModalElements.name.textContent = currentUser.name;
        userModalElements.email.textContent = currentUser.email;
        userModalElements.phone_number.textContent = currentUser.phone_number;
        userModalElements.dob.textContent = currentUser.dob;
    };

    // https://stackoverflow.com/questions/13459866/javascript-change-date-into-format-of-dd-mm-yyyy
    const convertDate = (inputFormat) => {
        function pad(s) { return (s < 10) ? '0' + s : s; }
        var d = new Date(inputFormat);
        return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/');
    };

    // https://stackoverflow.com/questions/2706125/javascript-function-to-add-x-months-to-a-date
    const addMonths = (date, months) => {
        var d = date.getDate();
        date.setMonth(date.getMonth() + +months);
        if (date.getDate() != d) {
          date.setDate(0);
        }
        return convertDate(date);
    };

    document.querySelector('.exit-modal-container i').addEventListener('click', closeUserModal);
} 
