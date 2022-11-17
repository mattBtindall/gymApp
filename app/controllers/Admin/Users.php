<?php
    class Users extends Controller {
        public function __construct() {
            $this->userModel = $this->model('User');
        }

        public function register() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phone_number' => trim($_POST['phone_number']),
                    'est' => trim($_POST['est']),
                    'description' => trim($_POST['description']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'name_err' => '',
                    'email_err' => '',
                    'phone_number_err' => '',
                    'est_err' => '',
                    'description_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                if (empty($data['name'])) {
                    $data['nameErr'] = 'Please enter a name';
                }

                if (empty($data['email'])) {
                    $data['emailErr'] = 'Please enter an email';
                }

                if (empty($data['phone_number'])) {
                    $data['phone_number_err'] = 'Please enter a phone number';
                }

                if (empty($data['est'])) {
                    $data['est_err'] = 'Please enter the year established';
                } else if (strlen($data['est']) != 4) {
                    $data['est_err'] = 'Please enter a valid year';
                }

                if (empty($data['description'])) {
                    $data['description_err'] = 'Please enter a business description';
                }

                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter a password';
                } else if (strlen($data['password']) < 6) {
                    $data['password_err'] = 'Please enter 6 or more characters for the password';
                }

                if (empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please enter a value for confirm password';
                } else {
                    if ($data['password'] !== $data['confirm_password']) {
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                }

                if (empty($data['name_err']) && empty($data['email_err']) && empty($data['phone_number_err']) && empty($data['est_err']) && empty($data['description_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                    // Remove errors & 'confirmPassword'
                    array_splice($data, 6, count($data) - 1);

                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    if ($this->userModel->register($data)) {
                        flash('register_success', 'You are registered and can login');
                        redirect('/users/login');
                    } else {
                        flash('register_failed', 'For some reason the registration failed, please try register again');
                        redirect('/users/register');
                    }
                } else {
                    $this->view(AREA . '/users/register', $data);
                }

            } else {
                $data = [
                    'name' => '',
                    'email' => '',
                    'phone_number' => '',
                    'est' => '',
                    'description' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'name_err' => '',
                    'email_err' => '',
                    'phone_number_err' => '',
                    'est_err' => '',
                    'description_err' => '',
                    'password_err' => '',
                    'confirm_password_err' => ''
                ];

                $this->view(AREA . '/users/register', $data);
            }
        }

        public function login() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'email_err' => '',
                    'password_err' => '',
                ];

                if (empty($data['email'])) {
                    $data['email_err'] = 'Please enter an email';
                } else if (!$this->userModel->userExists($data['email'])) {
                    $data['email_err'] = 'User does not exist';
                }

                if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter a password';
                }

                if (empty($data['email_err']) && empty($data['password_err'])) {#
                    $user = $this->userModel->login($data['email'], $data['password']);
                    if ($user) {
                        $this->createUserSession($user);
                    } else {
                        $data['password_err'] = 'Incorrect password';
                        $this->view(AREA . '/users/login', $data);
                    }
                } else {
                    $this->view(AREA . '/users/login', $data);
                }
            } else {
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => '',
                    'password_err' => ''
                ];

                $this->view(AREA . '/users/login', $data);
            }
        }

        public function createUserSession($user) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            redirect('pages/index');
        }

        public function logout() {
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_email']);
            session_destroy();
            redirect('users/login');
        }

        // *** this needs testing *** //
        public function uploadImg() {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
            }
        }
    }