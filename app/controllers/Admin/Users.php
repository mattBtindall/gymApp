<?php
class Users extends Users_base {
    private $memberModel;
    private $activityModel;
    private $termModel;

    public function __construct() {
        // Used to filter through the whole data array when displaying account detials in profile
        $profileValuesToShow = [
            'name',
            'email',
            'est',
            'phone_number',
            'description'
        ];

        $this->memberModel = $this->model('Member');
        $this->activityModel = $this->model('Activity');
        $this->termModel = $this->model('Term');
        parent::__construct($profileValuesToShow);
    }

    public function index() {
        // dashboard

        redirect('/dashboard/index');
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
                $data['name_err'] = 'Please enter a name';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            } else {
                if ($this->userModel->emailExists($data['email'])) {
                    $data['email_err'] = 'Account already exists with this email, either login or create an account with a different email';
                }
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
                $userDbValues = [
                    'name',
                    'email',
                    'phone_number',
                    'est',
                    'description',
                    'password',
                    'img_url'
                ];

                $insertIntoDb = array_filter($data, function($key) use ($userDbValues) {
                    if (in_array($key, $userDbValues)) return true;
                }, ARRAY_FILTER_USE_KEY);
                $insertIntoDb['password'] = password_hash($insertIntoDb['password'], PASSWORD_DEFAULT);

                if ($this->userModel->register($insertIntoDb)) {
                    flash('register_success', 'You are registered and can login');
                    redirect('/users/login');
                } else {
                    flash('register_failed', 'For some reason the registration failed, please try register again');
                    redirect('/users/register');
                }
            } else {
                // Maybe remove this?
                $this->view('/users/register', $data);
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
            $this->view('/users/register', $data);
        }
    }

    public function profile() {
        $data = parent::profile();
        $terms = $this->termModel->getActiveTerms($_SESSION['user_id']);
        foreach($terms as &$term) {
            $term['created_at'] = formatForOutput($term['created_at']) ;
            $plural = $term['term_multiplier'] > 1 ? 's' : '';
            $term['term_output'] = $term['term_multiplier'] . ' ' . $term['term'] . $plural;
        }
        $data['terms'] = $terms;
        $this->view('/users/profile', $data);
    }

    public function searchDb($query, $filter = 'all')  {
        $users = parent::searchDb($query, $filter);
        $members = $this->memberModel->getAllRelevantMemberships();
        $activity = $this->activityModel->getMembersActivityById($_SESSION['user_id'], 'admin_id');
        $users = $this->joinUserMembers($users, $members, $activity);
        if ($filter === 'clients') {
            $users = $this->searchFilterClients($users);
        } else if ($filter === 'active-members') {
            $users = $this->searchFilterActiveMembers($users);
        }
        echo json_encode($users);
    }

    private function searchFilterClients($users) {
        // all members - clients have a status key
        return array_values(array_filter($users, fn($user) => array_key_exists('status', $user)));
    }

    private function searchFilterActiveMembers($users) {
        // wrapped in array_values as array_filter: 'Array keys are preserved, and may result in gaps if the array was indexed. The result array can be reindexed using the array_values() function'
        return array_values(array_filter($users, fn($user) => array_key_exists('status', $user) && $user['status'] === 'active'));
    }

    public function getUserData() {
        // called by ajax request
        if (!isLoggedIn()) {
           echo '{}';
           return;
        }

        $users = parent::getUserData();
        $members = $this->memberModel->getAllRelevantMemberships();
        $activity = $this->activityModel->getMembersActivityById($_SESSION['user_id'], 'admin_id');
        echo json_encode($this->joinUserMembers($users, $members, $activity));
    }
}
