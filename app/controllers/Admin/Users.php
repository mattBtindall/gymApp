<?php
    class Users extends Controller {
        public function __construct() {

        }

        public function register() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phoneNumber' => trim($_POST['phoneNumber']),
                    'est' => trim($_POST['est']),
                    'description' => trim($_POST['description']),
                    'password' => trim($_POST['password']),
                    'confirmPassword' => trim($_POST['confirmPassword']),
                    'nameErr' => '',
                    'emailErr' => '',
                    'phoneNumberErr' => '',
                    'estErr' => '',
                    'descriptionErr' => '',
                    'passwordErr' => '',
                    'confirmPasswordErr' => ''
                ];

                if (empty($data['name'])) {
                    $data['nameErr'] = 'Please enter a name';
                }

                if (empty($data['email'])) {
                    $data['emailErr'] = 'Please enter an email';
                }

                if (empty($data['phoneNumber'])) {
                    $data['phoneNumberErr'] = 'Please enter a phone number';
                }

                if (empty($data['est'])) {
                    $data['estErr'] = 'Please enter the year established';
                } else if (strlen($data['est']) != 4) {
                    $data['estErr'] = 'Please enter a valid year';
                }

                if (empty($data['description'])) {
                    $data['descriptionErr'] = 'Please enter a business description';
                }

                if (empty($data['password'])) {
                    $data['passwordErr'] = 'Please enter a value for password';
                } else if (strlen($data['password']) < 6) {
                    $data['passwordErr'] = 'Please enter 6 or more characters for the password';
                }

                if (empty($data['confirmPassword'])) {
                    $data['confirmPasswordErr'] = 'Please enter a value for confirm password';
                } else {
                    if ($data['password'] !== $data['confirmPassword']) {
                        $data['confirmPasswordErr'] = 'Passwords do not match';
                    }
                }

                if (empty($data['nameErr']) && empty($data['emailErr']) && empty($data['phoneNumberErr']) && empty($data['estErr']) && empty($data['descriptionErr']) && empty($data['passwordErr']) && empty($data['confirmPasswordErr'])) {
                    die('its okay');
                } else {
                    $this->view(AREA . '/users/register', $data);
                }

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