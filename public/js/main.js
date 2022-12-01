'use strict';
window.onload = init();

function init() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');
    const searchBar = document.querySelector('.search-bar');
    const searchModal = document.querySelector('.search-bar-modal');
    const searchModalOutput = searchModal.querySelector('.search-bar-modal__output');
    const userModal = document.querySelector('.user-modal');
    const xhr = new XMLHttpRequest();
    let modalOpen = false;
    // store ajax results in variable
    // make sure you don't store all
    const isDescendant = (child, parent) => parent.contains(child);

    // Outputs rows from db using the template in navbar.php
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
            searchModalOutput.appendChild(rowBody);
        }

        data.forEach(rowData => createRow(rowData));
    };

    // Outputs a message in search bar
    const displayEmptySearchModalMessage = (message) => {
        searchModalOutput.innerHTML = "";
        const emptySearchTemplate = document.getElementById('empty-searchbar-msg');
        const emptySearchContainer = document.importNode(emptySearchTemplate.content, true);
        emptySearchContainer.querySelector('span').textContent = message;
        searchModalOutput.appendChild(emptySearchContainer);
    };

    // Closes the searchbar searchModal
    const closeSearchModal = () => {
        modalOpen = false;
        searchModal.classList.remove('active');
        document.body.classList.remove('overlay-active');
        displayEmptySearchModalMessage('Type a name in the search bar');
        searchBar.value = "";
    };


    // Opens user profile modal
    const openUserModal = (e) => {
        if (e.target.classList.contains('account-link')) {
            closeSearchModal();
            modalOpen = true;
            document.body.classList.add('overlay-active');
            userModal.classList.add('active');
        }
    }

    const closeUserModal = (e) => {
        modalOpen = false;
        userModal.classList.remove('active');
        document.body.classList.remove('overlay-active');
    }

    if (!searchBar || !fileInput || !searchModalOutput) {
        return;
    }

    searchModalOutput.addEventListener('click', openUserModal);
    document.querySelector('.exit-modal-container i').addEventListener('click', closeUserModal);

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

    searchBar.addEventListener('focus', () => {
        modalOpen = true;
        searchModal.classList.add('active');
        document.body.classList.add('overlay-active');
    });

    // Search for user using XHR
    searchBar.addEventListener('keyup', e => {
        if (!e.target.value.length) {
            displayEmptySearchModalMessage('Type a name in the search bar');
            return;
        }

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Output data from db
                if (this.responseText) {
                    displaySearchResults(JSON.parse(this.responseText));
                } else {
                    displayEmptySearchModalMessage('No user found with this name');
                }
            }
        };

        // truncate string after /Admin/ or /admin/ or /User/ or /user/
        // below gets base url in javascript
        const url = window.location.href;
        const areas = ['/Admin/', '/admin/', '/User/', '/user/'];
        let baseUrl = '';

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
