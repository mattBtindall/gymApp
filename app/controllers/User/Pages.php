<?php
    class Pages extends Controller {
        public function __construct(){

        }

        public function index() {
            $data = [
                'title' => 'Welcome User',
                'description' => ''
            ];

            $this->view(AREA . '/pages/index', $data);
        }

        public function about() {
            $data = [
                'title' => 'About us User'
            ];

            $this->view(AREA . '/pages/about', $data);
        }
    }