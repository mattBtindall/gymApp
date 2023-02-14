<?php
class Activitys extends Controller {
    private $activityModel;
    private $membersModel;

    public function __construct() {
        $this->activityModel = $this->model('Activity');
        $this->membersModel = $this->model('Member');
    }

    public function index() {
        $data = $this->activityModel->getMembersUserActivity($_SESSION['user_id']);
        $this->view('/activity/index', $data);
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

        $row = $this->activityModel->logUser($user_id, $_SESSION['user_id'], $active);
        $row = $row ? json_encode(formatActivity($row)) : '{}';
        echo $row;
    }

    public function getMembersActivity($admin_id) {
        $activity = $this->activityModel->getMembersActivity($admin_id);
        echo $activity ? json_encode($activity) : '{}';
    }

    public function getMemberActivity($admin_id, $user_id) {
        $activity = $this->activityModel->getMemberActivity($admin_id, $user_id);
        echo $activity ? json_encode($activity) : '{}';
    }
}
