<?php
class Pages extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function index() {
        // if logged in redirect to the log
        if (isLoggedIn()) {
            redirect('/users/index');
        }

        $data = [
            'title' => 'Welcome Admin',
            'description' =>'Welcome to the gymApp admin area, from here you can login to your business account, or register a new account. Got the wrong area? <a href="' . URL_ROOT_BASE . '/User">Click here for the user area</a>.'
        ];

        $this->view('/pages/index', $data);
    }

    public function about() {
        $data = [
            'title' => 'About gymApp'
        ];

        $this->view('/pages/about', $data);
    }
}