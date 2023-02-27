export const userData = {
    get() {return this.userDataStorage},
    set(newUserData) {this.userDataStorage = newUserData},
    userDataStorage: 0
};

export const activity = {
    get() {return this.activityStorage},
    set(newActivity) {this.activityStorage = newActivity},
    activityStorage: 0
}

export const modals = {};

export function getData(url) {
    return fetch(url)
    .then(response => response.json())
    .catch(e => console.log(e));
}

export function sendAjax(url) {
    fetch(url);
}

export function getPhpMethodUrl(phpMethod, param = "") {
    // truncate string after /Admin/ or /admin/ or /User/ or /user/
    // gets base url
    const url = window.location.href;
    const areas = ['Admin', 'admin', 'User', 'user'];
    let baseUrl = '';

    for (const area of areas) {
        if (url.indexOf(area) > 0) { // gets correct area
            baseUrl = url.slice(0, url.indexOf(area) + area.length);
            break;
        }
    }
    return baseUrl + phpMethod + param;
}

export function isAdmin() {
    const url = getPhpMethodUrl('/Users/isAdmin');
    return getData(url);
}

export function capitalise(str) {
    const words = str.split(' ');
    let capitalisedWords = '';
    words.forEach(word => {
        const firstLetter = word.charAt(0).toUpperCase();
        const restOfWord = word.slice(1);
        capitalisedWords += firstLetter + restOfWord + ' ';
    });
    return capitalisedWords.trim();
}

export function getMembershipStatusClasses(status, type) {
    let htmlClass = '';
    switch (status) {
        case 'expired' :
           htmlClass = 'danger';
            break;
        case 'future' :
            htmlClass = 'info';
            break;
        case 'active' :
            htmlClass = 'success';
            break;
        case 'revoked' :
            htmlClass = 'warning';
            break;
        default :
            htmlClass = 'danger';
    }
    return type.map(type => type + '-' + htmlClass);
}

// date is a string value e.g. '2023-02-20'
export function isDateToday(date) {
    const today = new Date();
    today.setHours(0,0,0,0);
    const yymmdd = date.split('-');
    yymmdd[1] -= 1; // the month is zero indexed
    const formattedDate = new Date(...yymmdd);
    formattedDate.setHours(0,0,0,0);
    return today.getTime() === formattedDate.getTime();
}
