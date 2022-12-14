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

function loadPageSpecificJavaScript() {
    // Get the URL of the current page
    $url = getUrl();

    // If the URL is empty, return without doing anything
    if (empty($url[1])) {
        return;
    }

    // removes the area as i don't load area specific js
    unset($url[0]);

    // loops through the controller and the method
    foreach($url as $property) {
        // Check if a JavaScript file for the current method exists
        if (file_exists(PUB_ROOT . '/js/' . $property . '.js')) {
            // If the file exists, include it in the page
            echo '<script src="' . URL_ROOT_BASE . '/js/' . $property . '.js"></script>';
        }
    }
}
