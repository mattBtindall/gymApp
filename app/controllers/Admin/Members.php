<?php
class Members extends Controller {
    public function __construct() {
        $this->membersModel = $this->model('Member');
    }

    public function index() {
        // var_dump($this->membersModel->getMembers($_SESSION['user_id']));
        $data = $this->membersModel->getMembers($_SESSION['user_id']);

        $this->view('/members/index', $data);
    }
}
