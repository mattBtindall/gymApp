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
                'img_url' => URL_ROOT_BASE . '/img/default_img.png',
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
                array_splice($data, 7, count($data) - 1);

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can login');
                    redirect('/users/login');
                } else {
                    flash('register_failed', 'For some reason the registration failed, please try register again');
                    redirect('/users/register');
                }
            } else {
                // Maybe remove this?
                $this->view(AREA . '/users/register', $data);
                // add return here to make sure the method ends here
                // rule of thumb return as fast as possible
                // make less indented
                // further to the left is better
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

    public function profile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $_FILES['file'];

            $fileName    = $_FILES['file']['name'];
            $fileTmpName = $_FILES['file']['tmp_name']; // temporary location of the file
            $fileSize    = $_FILES['file']['size'];
            $fileErr     = $_FILES['file']['error'];
            $fileType    = $_FILES['file']['type'];
            $err         = null;

            $fileExt = explode('.', $fileName); // 'image.png' -> ['image','png']
            $fileActualExt = strtolower(end($fileExt));
            $allowed = ['jpg', 'jpeg', 'png'];

            $data = $this->userModel->selectUserById($_SESSION['user_id']);
            
            if (!in_array($fileActualExt, $allowed)) { // check for correct file type
                // flash('img_upload_failed', 'Please upload a jpg or png file', 'alert alert-danger');
                // $this->view(AREA. '/users/profile', $data);
                $err = 'Please upload a jpg or png file';
            } elseif ($fileErr !== 0) {
                $err = 'There was an error uploading your file';
            } elseif ($fileSize > MAX_IMG_SIZE) {
                $err = 'File size too large'; 
            }

            if ($err) {
                flash('img_upload_failed', $err, 'alert alert-danger');
                $this->view(AREA. '/users/profile', $data);
            } 

            $fileNameNew = uniqid('', true) . '.' . $fileActualExt;
            // $fileDestination = URL_ROOT_BASE . '/img/' . $fileNameNew;
            $fileDestination = PUB_ROOT . '/img/' . $fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
            // Create a link in the DB
            // $this->userModel->uploadImg($fileDestination);

        } else {
            if (!isLoggedIn() || !$this->userModel->isAdmin()) {
                return;
            }

            $data = $this->userModel->selectUserById($_SESSION['user_id']);

            $this->view(AREA . '/users/profile', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        redirect('users/profile');
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
         
    }
}