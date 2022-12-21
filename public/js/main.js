import { SearchModal } from "./SearchModal.js";
import { UserModal } from "./UserModal.js";
import { modals } from "./utils.js";

// varialbes are injected via php
if (!openModal) {
    openModal = 0;
    currentUserId = 0;
}
console.log(openModal);
console.log(currentUserId);

window.onload = function() {
    modals.search = new SearchModal();
    modals.user = new UserModal(openModal, currentUserId);

    // if document querySelector searchBar is not null load the modals

    // if profile page then load profile page module

    // const openUserModal = (data) => {
    //     if (openModal) {
    //         const user = data.filter(user => user.id == currentUserId)[0];
    //         modals.user.setModal(user);
    //     }
    // }
    // getUserData(window.location.href + '/getMembersData', openUserModal);

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
