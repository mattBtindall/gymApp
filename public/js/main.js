'use strict';
window.onload = init();

function init() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');
    const searchBar = document.querySelector('.search-bar');
    const popover = document.querySelector('.search-bar-popover');
    const popoverOutput = popover.querySelector('.search-bar-popover__output');
    const xhr = new XMLHttpRequest();
    const isDescendant = (child, parent) => parent.contains(child);
    let popoverOpen = false;

    // Toggle btn for image upload in profile
    if (fileInput) {
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
    }

    // Search bar popover
    if (searchBar) {
        document.body.addEventListener('click', e => {
            if (!popoverOpen) {
                return;
            }

            if (e.target !== searchBar && e.target !== popover && !isDescendant(e.target, popover)) {
                popoverOpen = false;
                popover.classList.remove('active');
                document.body.classList.remove('overlay-active');
                searchBar.value = "";
            }
        });

        searchBar.addEventListener('focus', () => {
            popoverOpen = true;
            popover.classList.add('active');
            document.body.classList.add('overlay-active');
        });

        // Search for user
        searchBar.addEventListener('keyup', e => {
            if (!e.target.value.length) {
                // display "Type name in search bar" if not already showing
                return;
            }

            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // popoverOutput.innerHTML = this.responseText;
                    // console.dir(JSON.parse(this.responseText));
                    console.dir(this.responseText);
                }
            };

            // truncate string after /Admin/ or /admin/ or /User/ /user/
            // below gets base url in javascript
            console.log(window.location.href.slice(0, window.location.href.indexOf('Admin') + 'Admin'.length));
            xhr.open("GET", "http://localhost/gymApp/Admin/Users/searchDb/" + e.target.value, true);
            xhr.send();
        });
    }
}
