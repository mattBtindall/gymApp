<?php
    class Users extends Controller{
        public function __construct() {

        }

        public function register() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
            } else {
                $data = [
                    'name' => '',
                    'email' => '',
                    'phoneNumber' => '',
                    'est' => '',
                    'description' => '',
                    'password' => '',
                    'confirmPassword' => '',
                    'nameErr' => '',
                    'emailErr' => '',
                    'phoneNumberErr' => '',
                    'estErr' => '',
                    'descriptionErr' => '',
                    'passwordErr' => '',
                    'confirmPasswordErr' => ''
                ];

                $this->view(AREA . '/users/register', $data);
            }
        }

        public function login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            } else {
                $data = [
                    'email' => '',
                    'password' => '',
                    'emailErr' => '',
                    'passwordErr' => ''
                ];

                $this->view(AREA . '/users/login', $data);
            }
        }
    }