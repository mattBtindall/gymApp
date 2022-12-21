import { SearchModal } from "./SearchModal.js";
import { UserModal } from "./UserModal.js";
import { modals, getUserData, userData } from "./utils.js";

// varialbes are injected via php
// if (!openModal) {
//     openModal = 0;
//     currentUserId = 0;
// }
// console.log(openModal);
// console.log(currentUserId);

window.onload = function() {
    // if admin logged in get user data
    // getUserData(window.location.href + '/getMembersData', openUserModal);
    getUserData('http://localhost/gymApp/Admin/members/getMembersData', () => console.log('data: ' + userData));


    modals.search = new SearchModal();
    modals.user = new UserModal(openModal, currentUserId);

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
