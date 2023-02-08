<?php
/*
* Base controller
* Loads the models and views
*/

class Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        // if user is logged in but doesn't exist in the current areas database
        if (isLoggedIn() && !$this->userModel->userExists($_SESSION['user_email'])) {
            $oppositeArea = getOppositeArea();
            flash('incorrect_area', 'Please log out of your current account before accessing the ' . AREA .  ' area', 'alert alert-danger');
            header('location: '. URL_ROOT_BASE . '/' . $oppositeArea . '/users/profile');
        }

    }

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