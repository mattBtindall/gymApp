<?php
class Members extends Controller {
    public function __construct() {
        $this->membersModel = $this->model('Member');
        $this->members = $this->membersModel->getMembers($_SESSION['user_id']);
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            var_dump($_POST);

            $modal = [
                'term' => trim($_POST['term']),
                'start_date' => trim($_POST['start_date']),
                'expiry_date' => trim($_POST['expiry_date']),
                'term_err' => '',
                'start_date_err' => '',
                'expiry_date_err' => ''
            ];

            if (empty($modal['term']) || $modal['term'] === 'please_select') {
                $modal['term_err'] = 'Please select a term.';
            }

            if (empty($modal['start_date'])) {
                $modal['start_date_err'] = 'Please select a start date.';
            }

            if ($modal['term'] === 'custom' && empty($modal['expiry_date'])) {
                $modal['expiry_date_err'] = 'Please select an expiry date.';
            }
            
            // if ($this->membersModel->addMembership()) {
            //     // flash message here
            // } else {
            //     // flash message here
            // }
            $data = [
                'modal' => $modal,
                'members' => $this->members
            ];
            
        } else {
            $data = [
                'modal' => [
                    'term' => '',
                    'start_date' => '',
                    'expiry_date' => '',
                    'term_err' => '',
                    'start_date_err' => '',
                    'expiry_date_err' => ''
                ],
                'members' => $this->members
            ];
        }
        
        // View is loaded regardless
        $this->view('members/index', $data); 
    }

    public function getMembersData() {
        // this is called from an ajax call from the frontend
        // echo json_encode($this->membersModal->getMembers($_SESSION['user_id']));
        echo $this->membersModel->getMembers($_SESSION['user_id']) ? json_encode($this->membersModel->getMembers($_SESSION['user_id'])) : '{}';
    }
}
