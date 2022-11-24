<?php
class Users_base extends Controller {
    public function __construct($profileValuesToShow) {
        $this->userModel = $this->model('User');
        $this->profileValuesToShow = $profileValuesToShow;
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

            if (empty($data['email_err']) && empty($data['password_err'])) {
                $user = $this->userModel->login($data['email'], $data['password']);
                if ($user) {
                    // Login here
                    $this->createUserSession($user);
                } else {
                    $data['password_err'] = 'Incorrect password';
                    $this->view('/users/login', $data);
                }
            } else {
                $this->view('/users/login', $data);
            }
        } else {
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            $this->view('/users/login', $data);
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
                $this->view('/users/profile', $data);
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
            if (!isLoggedIn()) {
                // in here check for admin when trying to access admin
                return;
            }

            $data = $this->seperateProfileData($data);

            $this->view('/users/profile', $data);
        }
    }

    public function profile_edit($id) {
        $data = $this->userModel->selectUserById($id);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            var_dump($_POST);

            // $updateData = [
            //     'name' => trim($_POST['name']),
            //     'email' => trim($_POST['email']),
            //     'est' => trim($_POST['est']),
            //     'phone_number' => trim($_POST['phone_number']),
            //     'description' => trim($_POST['description']),
            // ];

            // // if value is blank or hasn't changed don't update
            // $updateData = array_filter($updateData, function($val, $key) use ($data) {
            //     return $val && $val != $data[$key];
            // }, ARRAY_FILTER_USE_BOTH);

            // if ($this->userModel->updateUser($updateData, $id)) {
            //     // get fresh data here
            //     flash('profile_update_success', 'Updated profile details');
            //     $this->view('/users/profile', $this->userModel->selectUserById($id));
            // } else {
            //     flash('profile_update_fail', 'Failed to update profile', 'alert alert-danger');
            //     $this->view('/users/profile_edit', $data);
            // }
        } else {
            $data = $this->seperateProfileData($data);
            $this->view('/users/profile_edit', $data);
        }
    }

    public function seperateProfileData($data) {
        // used to seperate values that will be displayed
        $dataToShow = array_filter($data,
            fn($key) => in_array($key, $this->profileValuesToShow),
            ARRAY_FILTER_USE_KEY
        );

        return [
            'to_show' => $dataToShow,
            'backend' => [
                'id' => $data['id'],
                'img_url' => $data['img_url']
            ]
        ];
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_email'] = $user->email;
        redirect('/users/profile');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        session_destroy();
        redirect('/users/login');
    }
}