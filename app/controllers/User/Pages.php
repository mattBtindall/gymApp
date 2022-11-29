<?php
class Pages extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function index() {
        $data = [
            'title' => 'Welcome User',
            'description' => 'Welcome to the gymApp User area. The gymApp allows you to gain access to gyms that use the gymApp system. All you need to do is create an account and then, once the repsective gym has granted your account access, your account will be assigned a unqiue barcode which you can scan to gain entry to the gym. Please see the list of popular gymApp gyms below!'
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