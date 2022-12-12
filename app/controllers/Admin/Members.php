<?php
class Members extends Controller {
    public function __construct() {
        $this->membersModel = $this->model('Member');
    }

    public function index() {
        $members = $this->membersModel->getMembers($_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        } else {
            $this->view('members/index', $members);
        }
    }
}
