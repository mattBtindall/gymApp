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
        $data = $this->userModel->selectUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Uploading an image
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

            if (!in_array($fileActualExt, $allowed)) { // check for correct file type
                $err = 'Please upload a jpg or png file';
            } elseif ($fileErr !== 0) { // check for err during upload
                $err = 'There was an error uploading your file';
            } elseif ($fileSize > MAX_IMG_SIZE) { // check for img size
                $err = 'File size too large';
            }

            if ($err) {
                flash('img_upload_failed', $err, 'alert alert-danger');
                $this->view(AREA. '/users/profile', $data);
            }

            $fileNameNew = uniqid('', true) . '.' . $fileActualExt; // create unique id for photo based on time of upload
            $fileDestination = PUB_ROOT . '/img/' . $fileNameNew;
            $fileLocation = URL_ROOT_BASE . '/img/' . $fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
            if ($this->userModel->uploadImg($fileLocation, $_SESSION['user_id'])) {
                flash('img_upload_success', 'Image uploaded successfully');
                redirect('users/profile');
            }
        } else {
            if (!isLoggedIn() || !$this->userModel->isAdmin()) {
                return;
            }

            $this->view(AREA . '/users/profile', $data);
        }
    }

    public function profile_edit($id) {
        $data = $this->userModel->selectUserById($id);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $updateData = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'est' => trim($_POST['est']),
                'phone_number' => trim($_POST['phone_number']),
                'description' => trim($_POST['description']),
            ];

            // if value is blank or hasn't changed don't update
            $updateData = array_filter($updateData, function($val, $key) use ($data) {
                return $val && $val != $data->$key;
            }, ARRAY_FILTER_USE_BOTH);

            if ($this->userModel->updateUser($updateData, $id)) {
                // get fresh data here
                flash('profile_update_success', 'Updated profile details');
                $this->view(AREA . '/users/profile', $this->userModel->selectUserById($id));
            } else {
                flash('profile_update_fail', 'Failed to update profile', 'alert alert-danger');
                $this->view(AREA . '/users/profile_edit', $data);
            }
        } else {
            $this->view(AREA . '/users/profile_edit', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        $this->view(AREA . '/users/profile', $user);
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