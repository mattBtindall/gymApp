<?php

class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone_number']),
                'dob' => trim($_POST['dob']),
                'gender' => trim($_POST['gender']),
                'img_url' => URL_ROOT_BASE . '/img/default_img.png',
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'dob_err' => '',
                'gender_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            var_dump($data);

            if (empty($data['name'])) {
                $data['nameErr'] = 'Please enter a name';
            }

            if (empty($data['email'])) {
                $data['emailErr'] = 'Please enter an email';
            }

            if (empty($data['phone_number'])) {
                $data['phone_number_err'] = 'Please enter a phone number';
            }

            // if (empty($data['dob'])) {
            //     $data['dob_err'] = 'Please enter a date of birth';
            // } else if (strlen($data['est']) != 4) {
            //     $data['est_err'] = 'Please enter a valid year';
            // }

            // if (empty($data['description'])) {
            //     $data['description_err'] = 'Please enter a business description';
            // }

            // if (empty($data['password'])) {
            //     $data['password_err'] = 'Please enter a password';
            // } else if (strlen($data['password']) < 6) {
            //     $data['password_err'] = 'Please enter 6 or more characters for the password';
            // }

            // if (empty($data['confirm_password'])) {
            //     $data['confirm_password_err'] = 'Please enter a value for confirm password';
            // } else {
            //     if ($data['password'] !== $data['confirm_password']) {
            //         $data['confirm_password_err'] = 'Passwords do not match';
            //     }
            // }

            // if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['est_err']) && empty($data['description_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
            //     // Remove errors & 'confirmPassword'
            //     array_splice($data, 7, count($data) - 1);

            //     $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            //     if ($this->userModel->register($data)) {
            //         flash('register_success', 'You are registered and can login');
            //         redirect('/users/login');
            //     } else {
            //         flash('register_failed', 'For some reason the registration failed, please try register again');
            //         redirect('/users/register');
            //     }
            // } else {
            //     // Maybe remove this?
            //     $this->view(AREA . '/users/register', $data);
            //     // add return here to make sure the method ends here
            //     // rule of thumb return as fast as possible
            //     // make less indented
            //     // further to the left is better
            // }
        } else {
            $data = [
                'name' => '',
                'email' => '',
                'phone_number' => '',
                'dob' => '',
                'gender' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'dob_err' => '',
                'gender_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            $this->view(AREA . '/users/register', $data);
        }
    }
}