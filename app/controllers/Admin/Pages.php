<?php
    class Pages extends Controller {
        public function __construct(){

        }

        public function index() {
            $data = [
                'title' => 'Welcome Admin',
                'description' =>'Welcome to the gymApp admin area, from here you can login to your business account. Got the wrong area? <a href=""> </a>'
            ];

            $this->view('pages/index', $data);
        }

        public function about() {
            $data = [
                'title' => 'About us Admin'
            ];

            $this->view('pages/about', $data);
        }
    }