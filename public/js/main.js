import { SearchModal } from "./SearchModal.js";
import { UserModal } from "./UserModal.js";
import { modals, getData, userData, getPhpMethodUrl } from "./utils.js";
import * as eventListeners from "./generalEventListeners.js";

window.onload = function() {
    contentSpecificJs();
    const url = getPhpMethodUrl("Users/getUserData");
    // checks to see if logged in then fetches the data from the opposite area
    getData(url)
        .then(data => {
            userData.set(data);
            eventListeners.setBodyClick();
            modals.search = new SearchModal();
            modals.user = new UserModal();
        });
}

function contentSpecificJs() {
    // if fileUpload elements are present load setImgUpload from genEventListeners
    if (document.querySelector('.upload')) {
        eventListeners.setImgUpload();
    }

    // if membership tab present load setMembershiptab from genEventListeners
    if (document.querySelector('.expiry-date')) {
        eventListeners.setMembershipTab();
    }
}
