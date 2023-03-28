<?php
class Activitys extends Activitys_base {
    private $activityModel;
    private $membersModel;

    public function __construct() {
        $this->activityModel = $this->model('Activity');
        $this->membersModel = $this->model('Member');
        parent::__construct('getMembersActivityAdmin');
    }

    public function logUser($user_id) {
        $memberships = $this->membersModel->getAllRelevantMemberships();
        $membership = array_values(array_filter($memberships, fn($value) => $value['user_id'] == $user_id))[0];
        $data = [
            'status' => getMembershipStatus($membership['start_date'], $membership['expiry_date']),
            'user_id' => $user_id,
            'admin_id' => $_SESSION['user_id'],
            'term_id' => $membership['term_id'],
            'membership_id' => $membership['membership_id'],
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
