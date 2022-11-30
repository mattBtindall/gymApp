'use strict';
window.onload = init();

function init() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');
    const searchBar = document.querySelector('.search-bar');
    const popover = document.querySelector('.search-bar-popover');
    const popoverOutput = popover.querySelector('.search-bar-popover__output');
    // const accountLink = document.querySelector('.account-link');

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

            // nameOutput.addEventListener('click', showAccountModal);
        }

        data.forEach(rowData => createRow(rowData));
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
            popoverOutput.innerHTML = "";
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
            // display "Type name in search bar" if not already showing
            return;
        }

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) { // data from db
                displaySearchResults(JSON.parse(this.responseText));
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
