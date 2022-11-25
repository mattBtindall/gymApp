<?php
function redirect($page) {
    header('location: '. URL_ROOT . $page);
}

function getOppositeArea() {
    return AREA === 'Admin' ? 'User' : 'Admin';
}