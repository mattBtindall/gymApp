<?php
function redirect($page) {
    header('location: '. URL_ROOT . $page);
}

function getOppositeArea() {
    return AREA === 'Admin' ? 'User' : 'Admin';
}

function getUrl() {
    if (isset($_GET['url'])) {
        // remove ending '/' if there is one
        $url = rtrim($_GET['url'], '/');
        // make sure it doesn't contain any characters that a url shouldn't have
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // splits into array on '/' e.g. url = localhost/brad-trav-php-mvc/post/edit/1 $url = [post,edit,1]
        $url = explode('/', $url);
        return $url;
    }
}