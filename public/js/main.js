import { SearchModal } from "./SearchModal.js";
import { UserModal } from "./UserModal.js";
import { Terms } from "./Terms.js";
import { modals, getData, userData, getPhpMethodUrl, activity } from "./utils.js";
import * as eventListeners from "./generalEventListeners.js";
import { Activity } from "./Activity.js";

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

    // if on activity page
    if (document.querySelector('.activity-output')) {
        activity.set(new Activity({
            container: '.activity-output',
        }));
    }

    // if there is a search bar load the searchbar Modal class
    if (document.querySelector('.search-bar')) {
        modals.search = new SearchModal();
        // if searchbar present then users are logged in so they can open user modal
        modals.user = new UserModal();
    }

    // if there is a terms table lead the setTermsTable from genEventListeners
    if (document.querySelector('.terms-table')) {
        const term = new Terms();
    }
}
