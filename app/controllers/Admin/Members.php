<?php
class Members extends Controller {
    public function __construct() {
        $this->membersModel = $this->model('Member');
        $this->userModel = $this->model('User');
        $this->members = $this->membersModel->getMembers($_SESSION['user_id']);
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $modal = [
                'user_id' => trim($_POST['user_id']),
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
            } else if ($this->dateOverlap($modal['user_id'], $modal['start_date'])) {
                $modal['start_date_err'] = 'Please select a date that begins after the current membership expires';
            }

            if ($modal['term'] === 'custom' && empty($modal['expiry_date'])) {
                $modal['expiry_date_err'] = 'Please select an expiry date.';
            } else if ($modal['term'] === 'custom' && strtotime($modal['expiry_date']) <= strtotime($modal['start_date'])) {
                $modal['expiry_date_err'] = 'Please select an expiry date that is greater than the start date';
            }

            if (empty($modal['term_err']) && empty($modal['start_date_err']) && empty($modal['expiry_date_err'])) {
                // reset modal state so that it doesn't reopen
                $_SESSION['user_modal_state']['open'] = false;
                $_SESSION['user_modal_state']['user_id'] = 0;

                // set dates for membership
                $membershipDates = $this->generateMembershipDates($modal['term'], $modal['start_date'], $modal['expiry_date']);
                if ($this->membersModel->addMembership($membershipDates, $modal['user_id'])) {
                    $userName = $this->userModel->selectUserById($modal['user_id'], 'User')['name'];
                    $successMsg = "A membership has been successfully added for {$userName}";
                    flash('membership_assignment', $successMsg);
                    $this->members = $this->membersModel->getMembers($_SESSION['user_id']); // reload members so newly added member shows
                } else {
                    flash('membership_assignment', 'Membership assignment failed', 'alert alert-danger');
                }
            } else {
                // errors so tell javascript to open modal
                $_SESSION['user_modal_state']['open'] = true;
                $_SESSION['user_modal_state']['user_id'] = $modal['user_id'];
            }

            $data = [
                'modal' => $modal,
                'members' => $this->members,
            ];

        } else {
            $data = [
                'modal' => [
                    'user_id' => 0,
                    'term' => '',
                    'start_date' => '',
                    'expiry_date' => '',
                    'term_err' => '',
                    'start_date_err' => '',
                    'expiry_date_err' => ''
                ],
                'members' => $this->members,
            ];
        }

        $this->view('members/index', $data);
    }

    public function activity() {
        // get activity for this account


        $this->view('members/activity');
    }

    public function getMembersData() {
        // this is called from an ajax call from the frontend
        $jsonData = $this->members ? json_encode(($this->members)) : '{}';
        echo $jsonData;
    }

    private function dateOverlap($user_id, $startDate) {
        // get active memberships from the user
        $memberships = $this->membersModel->getMemberById($user_id);
        if (!$memberships) return false;

        // loop through all memberships here, not just one
        // see if there is an active membership
        $startDate = new DateTime($startDate);
        $formattedStartDate = $startDate->format('d/m/y');
        $hasOverlap = false;
        foreach ($memberships as $membership) {
            echo 'start date: ' . $formattedStartDate . '<br>';
            echo 'expiry date: ' . $membership['expiry_date'] . '<br>';
            if (strtotime($formattedStartDate) < strtotime($membership['expiry_date'])) {
                $hasOverlap = true;
            }
        }

        return $hasOverlap;
    }

    private function generateMembershipDates($term, $startDate, $endDate) {
        if (!$endDate) {
            $endDate = new DateTime($startDate);
            $endDate->modify('+' . $term . ' month');
        } else {
            $endDate = new DateTime($endDate);
        }
        $startDate = new DateTime($startDate);

        return [
            'term' => $term,
            'start_date' => $startDate->format('d/m/y'),
            'expiry_date' => $endDate->format('d/m/y')
        ];
    }
}
