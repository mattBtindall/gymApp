export let userData = {
    get() {return this.userDataStorage},
    set(newUserData) {this.userDataStorage = newUserData},
    userDataStorage: 0
};

export const modals = {};

export function getData(url, callback) {
    fetch(url)
    .then(response => response.json())
    .then(data => {
        if (callback) callback(data);
        return data;
    })
    .catch(e => console.log(e));
}

export function getPhpMethodUrl(phpMethod, param = "") {
    // truncate string after /Admin/ or /admin/ or /User/ or /user/
    // gets base url
    const url = window.location.href;
    const areas = ['/Admin/', '/admin/', '/User/', '/user/'];
    let baseUrl = '';

    for (const area of areas) {
        if (url.indexOf(area) > 0) { // gets correct area
            baseUrl = url.slice(0, url.indexOf(area) + area.length);
            break;
        }
    }
    return baseUrl + phpMethod + param;
}
