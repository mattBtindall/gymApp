'use strict';
window.onload = init();

function init() {
    const fileInput = document.getElementsByName('file')[0];
    const uploadbtn = document.querySelector('.upload');
    const searchBar = document.querySelector('.search-bar');
    const searchBarPopover = document.querySelector('.search-bar-popover');
    const isDescendant = (child, parent) => parent.contains(child);
    let searchBarPopoverOpen = false;

    if (fileInput) {
        const imgTypes = ['jpg', 'jpeg', 'png'];

        fileInput.addEventListener('change', function() {
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

    if (searchBar) {
        document.body.addEventListener('click', function(e) {
            if (!searchBarPopoverOpen) {
                return;
            }

            if (e.target !== searchBar && e.target !== searchBarPopover && !isDescendant(e.target, searchBarPopover)) {
                searchBarPopoverOpen = false;
                searchBarPopover.classList.remove('active');
                document.body.classList.remove('overlay-active');
            }
        });

        searchBar.addEventListener('focus', function() {
            searchBarPopoverOpen = true;
            searchBarPopover.classList.add('active');
            document.body.classList.add('overlay-active');
        });
    }
}
