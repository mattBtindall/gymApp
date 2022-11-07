<?php 
    /*
     * App core class
     * Creates URL & loads core controller
     * URL format - /controller/method/params
     */

    class Core {
        protected $currentController = 'Pages'; // sets default to pages - if there's no other controllers this is what's going to load 
        protected $currentMethod = 'index'; 
        protected $params = [];

        public function __construct() {
            $url = $this->getUrl();

            // Look in controllers for first index of $url
            // Remember we route everything through index.php so we get files relative to that not this actual file
            if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                // If exists set as current controller
                $this->currentController = ucwords($url[0]);
                // Unset the 0 index
                unset($url[0]);
            }   

            // Require the controller
            require_once '../app/controllers/' . $this->currentController . '.php';
            // Instantiate the class
            $this->currentController = new $this->currentController();

            // Check for second part of url 
            if (isset($url[1])) {
                // Check to see if method exists in controller
                if (method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
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