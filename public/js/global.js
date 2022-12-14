'use strict';
let userData = [];

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
