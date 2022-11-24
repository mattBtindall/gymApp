<?php 
    /*
     * Base controller
     * Loads the models and views
     */

    class Controller {
        // Load model
        public function model($model) {
            // Require model file
            require_once '../app/models/' . $model . '.php';

            // Instantiate model
            return new $model();
        }

        // Load view
        public function view($view, $data = []) {
            // Check for the view file
            if (file_exists('../app/views/'  . AREA . '/' . $view . '.php')) {
                require_once '../app/views/'  . AREA . '/' . $view . '.php';
            } else {
                // view doesn't exist
                die('View does not exist');
            }
        }
    }