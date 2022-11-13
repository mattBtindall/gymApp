<?php
    // *** within $this->view() check to see if the view exists in the AREA dir if it doesn't then see if the view exists in a joint dir so the dir without prepending AREA, obviously if it exists in there then load that *** // 

    class Pages extends Controller {
        public function __construct(){

        }

        public function index() {
            $data = [
                'title' => 'Welcome Admin',
                'description' =>'Welcome to the gymApp admin area, from here you can login to your business account, or register a new account. Got the wrong area? <a href="' . URL_ROOT_BASE . '/User">Click here for the user area</a>.'
            ];

            $this->view(AREA .'/pages/index', $data);
        }

        public function about() {
            $data = [
                'title' => 'About us Admin'
            ];

            $this->view(AREA . '/pages/about', $data);
        }
    }