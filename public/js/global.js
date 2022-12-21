'use strict';
let userData = [];
const modals = {};

function getUserData(url, callback) {
    fetch(url)
    .then(response => response.json())
    .then(data => {
        userData = data;
        if (callback) callback(data);
        return data;
    })
    .catch(e => console.log(e));
}

window.onload = function() {
    modals.search = new SearchModal();
    modals.user = new UserModal();

    const openUserModal = (data) => {
        if (openModal) {
            const user = data.filter(user => user.id == currentUserId)[0];
            modals.user.setModal(user);
        }
    }
    getUserData(window.location.href + '/getMembersData', openUserModal);

    document.body.addEventListener('click', e => {
        if (e.target === document.body) {
            for (const key in modals) {
                if (modals[key].modalOpen) {
                    modals[key].closeModal();
                }
            }
        }
    });
}