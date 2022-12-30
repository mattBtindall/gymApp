import { SearchModal } from "./SearchModal.js";
import { UserModal } from "./UserModal.js";
import { modals, getData, userData, getPhpMethodUrl } from "./utils.js";
import * as eventListeners from "./generalEventListeners.js";

window.onload = function() {
    const url = getPhpMethodUrl("/Users/getUserData");
    // checks to see if logged in then fetches the data from the opposite area
    getData(url)
    .then(data => {
            userData.set(data);
            eventListeners.setBodyClick();
            contentSpecificJs();
        });
}

function contentSpecificJs() {
    // if fileUpload elements are present load setImgUpload from genEventListeners
    if (document.querySelector('.upload')) {
        eventListeners.setImgUpload();
    }

    // if membership tab present load setMembershiptab from genEventListeners
    if (document.querySelector('.expiry-date')) {
        modals.user = new UserModal();
        eventListeners.setMembershipTab();
    }

    // if there is a search bar load the searchbar Modal class
    if (document.querySelector('.search-bar')) {
        modals.search = new SearchModal();
    }
}
