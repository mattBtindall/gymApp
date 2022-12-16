<?php
class Members extends Controller {
    public function __construct() {
        $this->membersModel = $this->model('Member');
        $this->members = $this->membersModel->getMembers($_SESSION['user_id']);
        var_dump($this->members);
    }

    public function index() {
        $members = $this->membersModel->getMembers($_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->membersModel->addMembership()) {
                // flash message here
            } else {
                // flash message here
            }

            $this->view('members/index', $members);
        } else {
            $this->view('members/index', $members);
        }
    }

    public function getMembersData() {
        // this is called from an ajax call from the frontend
        // echo json_encode($this->membersModal->getMembers($_SESSION['user_id']));
        echo $this->membersModel->getMembers($_SESSION['user_id']) ? json_encode($this->membersModel->getMembers($_SESSION['user_id'])) : '{}';
    }
}
