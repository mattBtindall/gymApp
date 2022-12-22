import { SearchModal } from "./SearchModal.js";
import { UserModal } from "./UserModal.js";
import { modals, getData, userData, getPhpMethodUrl } from "./utils.js";

window.onload = function() {
    getUserData();

    modals.search = new SearchModal();
    modals.user = new UserModal();

    document.body.addEventListener('click', e => {
        if (e.target === document.body) {
            for (const key in modals) {
                if (modals[key].open) {
                    modals[key].closeModal();
                }
            }
        }
    });
}

// makes ajax call to backend
// if logged in
// gets the correct data for either area
// for admin gets all users and all members
function getUserData() {
    const url = getPhpMethodUrl("Users/getUserData");
    // checks to see if logged in then fetches the data from the opposite area
    getData(url, function(data) {
        userData.set(data);
    });
}

