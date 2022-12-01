'use strict';
window.onload = init();

function init() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');
    const searchBar = document.querySelector('.search-bar');
    const popover = document.querySelector('.search-bar-popover');
    const popoverOutput = popover.querySelector('.search-bar-popover__output');
    const xhr = new XMLHttpRequest();
    let popoverOpen = false;
    const isDescendant = (child, parent) => parent.contains(child);

    // Outputs rows from db using the template in navbar.php
    const displaySearchResults = data => {
        popoverOutput.innerHTML = "";

        const createRow = (rowData) => {
            const rowTemplate = document.getElementById('row');
            const rowBody = document.importNode(rowTemplate.content, true);
            const nameOutput = rowBody.querySelector('.name');
            rowBody.querySelector('.row-img').src = rowData['img_url'];
            nameOutput.textContent = rowData['name'];
            rowBody.querySelector('.email').textContent = rowData['email'];
            rowBody.querySelector('.phone_number').textContent = rowData['phone_number'];
            popoverOutput.appendChild(rowBody);
        }

        data.forEach(rowData => createRow(rowData));
    }

    // Outputs a message in search bar
    const displayEmptySearchBarMessage = (message) => {
        popoverOutput.innerHTML = "";
        const emptySearchTemplate = document.getElementById('empty-searchbar-msg');
        const emptySearchContainer = document.importNode(emptySearchTemplate.content, true);
        console.log(emptySearchContainer);
        emptySearchContainer.querySelector('span').textContent = message;
        popoverOutput.appendChild(emptySearchContainer);
    }

    if (!searchBar || !fileInput) {
        return;
    }

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

    // Search bar popover
    document.body.addEventListener('click', e => {
        if (!popoverOpen) {
            return;
        }

        if (e.target !== searchBar && e.target !== popover && !isDescendant(e.target, popover)) {
            popoverOpen = false;
            popover.classList.remove('active');
            document.body.classList.remove('overlay-active');
            displayEmptySearchBarMessage('Type a name in the search bar');
            searchBar.value = "";
        }
    });

    searchBar.addEventListener('focus', () => {
        popoverOpen = true;
        popover.classList.add('active');
        document.body.classList.add('overlay-active');
    });

    // Search for user using XHR
    searchBar.addEventListener('keyup', e => {
        if (!e.target.value.length) {
            displayEmptySearchBarMessage('Type a name in the search bar');
            return;
        }

        xhr.onreadystatechange = function() {
            if (this.readyState !== 4 && this.status !== 200) {
                return;
            }

            // Output data from db
            if (this.responseText) {
                displaySearchResults(JSON.parse(this.responseText));
            } else {
                displayEmptySearchBarMessage('No user found with this name');
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
