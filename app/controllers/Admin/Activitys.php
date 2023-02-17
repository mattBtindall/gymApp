<?php
class Activitys extends Controller {
    private $activityModel;
    private $membersModel;

    public function __construct() {
        $this->activityModel = $this->model('Activity');
        $this->membersModel = $this->model('Member');
    }

    public function index($date = NULL) {
        $date = $date ?? 'NOW()';
        $data['activity'] = $this->activityModel->getMembersUserActivity($_SESSION['user_id'], $date);
        foreach ($data['activity'] as &$userActivity) {
            $userActivity = formatActivity($userActivity);
        }
        $this->view('/activity/index', $data);
    }

    public function logUser($user_id) {
        $memberships = $this->membersModel->getRelevantMemberships();
        $membership = array_values(array_filter($memberships, fn($value) => $value['user_id'] == $user_id))[0];
        $data = [
            'status' => getMembershipStatus($membership['start_date'], $membership['expiry_date']),
            'user_id' => $user_id,
            'admin_id' => $_SESSION['user_id'],
            'term_display_name' => $membership['term_display_name'],
            'membership_start_date' => $membership['start_date'],
            'membership_expiry_date' => $membership['expiry_date'],
        ];

        $row = $this->activityModel->logUser($data);
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
