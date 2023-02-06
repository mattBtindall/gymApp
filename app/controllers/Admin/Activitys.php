<?php
class Activitys extends Controller {
    public function __construct() {
        $this->activityModel = $this->model('Activity');
        $this->membersModel = $this->model('Member');
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        } else {

        }

        $this->view('/activity/index');
    }

    public function logUser($user_id) {
        $active = 0;
        $memberships = $this->membersModel->getTermMembershipByUserId($user_id);
        foreach ($memberships as $membership) {
            if (getMembershipStatus($membership['start_date'], $membership['expiry_date']) === 'active') {
                $active = 1;
                break;
            }
        }

        $this->activityModel->logUser($user_id, $_SESSION['user_id'], $active);
    }
}