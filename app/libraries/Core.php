<?php
    /*
     * App core class
     * Creates URL & loads core controller
     * URL format - /area/controller/method/params
     */

    class Core {
        protected $area = 'User';
        protected $currentController = 'Pages'; // sets default to pages - if there's no other controllers this is what's going to load
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct() {
            $url = $this->getUrl();

            if (isset($url[0]) && is_dir('../app/controllers/' . ucwords($url[0]))) {
                $this->area = ucwords($url[0]);
                unset($url[0]);
            }

            // set site wide variables
            define('AREA', $this->area);
            define('URL_ROOT', URL_ROOT_BASE . '/' . AREA);

            // Look in controllers for second index of $url
            // Remember we route everything through index.php so we get files relative to that not this actual file
            if (isset($url[1]) && file_exists('../app/controllers/' . $this->area . '/' . ucwords($url[1]) . '.php')) {
                // If exists set as current controller
                $this->currentController = ucwords($url[1]);
                // Unset the 0 index
                unset($url[1]);
            }

            // Require the controller
            require_once '../app/controllers/' . $this->area . '/' . $this->currentController . '.php';
            // Instantiate the class
            $this->currentController = new $this->currentController();

            // Check for second part of url
            if (isset($url[2])) {
                // Check to see if method exists in controller
                if (method_exists($this->currentController, $url[2])) {
                    $this->currentMethod = $url[2];
                    unset($url[2]);
                }
            }

            // Get params
            $this->params = $url ? array_values($url) : [];

            // Call a callback with an array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }

        public function getUrl() {
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
    }