'use strict';

window.onload = function () {
    let modalOpen = false;
    const searchBar = document.querySelector('.search-bar');
    const searchModal = document.querySelector('.search-bar-modal');
    const searchModalOutput = searchModal.querySelector('.search-bar-modal__output');
    let userData = [];
    const userModalElements = {
        modal: document.querySelector('.user-modal'),
        name: document.querySelector('.user-modal .name'),
        email: document.querySelector('.user-modal .email'),
        phone_number: document.querySelector('.user-modal .phone_number'),
        dob: document.querySelector('.user-modal .dob')
    }
    const xhr = new XMLHttpRequest();

    // User modal functions
    const setUserModal = (searchElement) => {
        const currentUserId = searchElement.closest('.search-modal__row').querySelector('.id').textContent;
        const currentUser = userData.filter(user => user.id == currentUserId)[0];

        userModalElements.name.textContent = currentUser.name;
        userModalElements.email.textContent = currentUser.email;
        userModalElements.phone_number.textContent = currentUser.phone_number;
        userModalElements.dob.textContent = currentUser.dob;
    };

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

    // Outputs rows from db using the template in modals.php
    const displaySearchResults = (data) => {
        searchModalOutput.innerHTML = "";
        const createRow = (rowData) => {
            const rowTemplate = document.getElementById('row');
            const rowBody = document.importNode(rowTemplate.content, true);
            const nameOutput = rowBody.querySelector('.name');
            rowBody.querySelector('.row-img').src = rowData['img_url'];
            nameOutput.textContent = rowData['name'];
            rowBody.querySelector('.email').textContent = rowData['email'];
            rowBody.querySelector('.phone_number').textContent = rowData['phone_number'];
            rowBody.querySelector('.id').textContent = rowData['id'];
            searchModalOutput.appendChild(rowBody);
        }

        data.forEach(rowData => createRow(rowData));
    };

    const displayEmptySearchModalMessage = (message) => {
        searchModalOutput.innerHTML = "";
        const emptySearchTemplate = document.getElementById('empty-searchbar-msg');
        const emptySearchContainer = document.importNode(emptySearchTemplate.content, true);
        emptySearchContainer.querySelector('span').textContent = message;
        searchModalOutput.appendChild(emptySearchContainer);
    };

    const openSearchModal = () => {
        modalOpen = true;
        searchModal.classList.add('active');
        document.body.classList.add('overlay-active');
    }

    const closeSearchModal = () => {
        modalOpen = false;
        searchModal.classList.remove('active');
        document.body.classList.remove('overlay-active');
        displayEmptySearchModalMessage('Type a name in the search bar');
        searchBar.value = "";
    };

    // Search bar searchModal
    document.body.addEventListener('click', e => {
        if (!modalOpen) {
            return;
        }

        if (e.target === document.body) {
            closeSearchModal();
            closeUserModal();
        }
    });

    searchBar.addEventListener('focus', openSearchModal);
    document.querySelector('.exit-modal-container i').addEventListener('click', closeUserModal);
    searchModalOutput.addEventListener('click', openUserModal);
    setMembershipTab();

    // Search for user using XHR
    searchBar.addEventListener('keyup', e => {
        if (!e.target.value.length) {
            displayEmptySearchModalMessage('Type a name in the search bar');
            return;
        }

        // truncate string after /Admin/ or /admin/ or /User/ or /user/
        // below gets base url
        const url = window.location.href;
        const areas = ['/Admin/', '/admin/', '/User/', '/user/'];
        let baseUrl = '';

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Output data from db
                if (this.responseText) {
                    userData = JSON.parse(this.responseText);
                    displaySearchResults(userData);
                } else {
                    displayEmptySearchModalMessage('No user found with this name');
                }
            }
        };

        for (const area of areas) {
            if (url.indexOf(area) > 0) { // gets correct area
                baseUrl = url.slice(0, url.indexOf(area) + area.length);
                break;
            }
        }

        xhr.open("GET", baseUrl + "Users/searchDb/" + e.target.value, true);
        xhr.send();
    });
}

function getMembershipDates() {
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
}

function setMembershipTab() {
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

function addMembership() {

}