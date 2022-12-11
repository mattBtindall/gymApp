'use strict';

window.onload = function () {
    const searchBar = document.querySelector('.search-bar');
    const searchModal = document.querySelector('.search-bar-modal');
    const searchModalOutput = searchModal.querySelector('.search-bar-modal__output');
    let userData = [];
    const xhr = new XMLHttpRequest();
    searchModalOutput.addEventListener('click', openUserModal);

    function displayEmptySearchModalMessage(message) {
        searchModalOutput.innerHTML = "";
        const emptySearchTemplate = document.getElementById('empty-searchbar-msg');
        const emptySearchContainer = document.importNode(emptySearchTemplate.content, true);
        emptySearchContainer.querySelector('span').textContent = message;
        searchModalOutput.appendChild(emptySearchContainer);
    };

    // Outputs rows from db using the template in modals.php
    function displaySearchResults(data) { 
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

    function openSearchModal() { 
        modalOpen = true;
        searchModal.classList.add('active');
        document.body.classList.add('overlay-active');
    }

    function closeSearchModal() {
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