'use strict';

window.onload = function () {
    let modalOpen = false;
    const searchBar = document.querySelector('.search-bar');
    const searchModal = document.querySelector('.search-bar-modal');
    const searchModalOutput = searchModal.querySelector('.search-bar-modal__output');
    const userModalElements = {
        modal: document.querySelector('.user-modal'),
        name: document.querySelector('.user-modal .name'),
        email: document.querySelector('.user-modal .email'),
        phone_number: document.querySelector('.user-modal .phone_number'),
        dob: document.querySelector('.user-modal .dob'),
        id: document.querySelector('.user-modal .id')
    }

    // User modal functions
    const setUserModal = (searchElement, parentSelector) => {
        const currentUserId = searchElement.closest(parentSelector).querySelector('.id').textContent;
        const currentUser = userData.filter(user => user.id == currentUserId)[0];
        userModalElements.name.textContent = currentUser.name;
        userModalElements.email.textContent = currentUser.email;
        userModalElements.phone_number.textContent = currentUser.phone_number;
        userModalElements.dob.textContent = currentUser.dob;
        userModalElements.id.value = currentUserId;
    };

    const openUserModal = (element, parentSelector) => {
        closeSearchModal();
        setUserModal(element, parentSelector);
        modalOpen = true;
        document.body.classList.add('overlay-active');
        userModalElements.modal.classList.add('active');
    };

    const closeUserModal = (e) => {
        modalOpen = false;
        userModalElements.modal.classList.remove('active');
        document.body.classList.remove('overlay-active');
    };

    // Outputs rows from db using the template in modals.php
    const displaySearchResults = (data) => {
        if (data === '{}') {
            displayEmptySearchModalMessage('No user found with this name');
            return;
        }

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

    const openSearchModal = (e) => {
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
    searchModalOutput.addEventListener('click', (e) => {
        if (e.target.classList.contains('account-link')) {
            openUserModal(e.target, '.search-modal__row');
        }
    });
    document.querySelector('.membership-table').addEventListener('click', (e) => {
        openUserModal(e.target, '.account-link');
    });
    setMembershipTab();

    // Search for user using XHR
    searchBar.addEventListener('keyup', e => {
        if (!e.target.value.length) {
            displayEmptySearchModalMessage('Type a name in the search bar');
            return;
        }

        // truncate string after /Admin/ or /admin/ or /User/ or /user/
        // below gets base url
        let url = window.location.href;
        const phpMethod =  "Users/searchDb/";
        const query = e.target.value;
        const areas = ['/Admin/', '/admin/', '/User/', '/user/'];
        let baseUrl = '';

        for (const area of areas) {
            if (url.indexOf(area) > 0) { // gets correct area
                baseUrl = url.slice(0, url.indexOf(area) + area.length);
                break;
            }
        }
        url = baseUrl + phpMethod + query,
        getUserData(url, displaySearchResults);
    });
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
