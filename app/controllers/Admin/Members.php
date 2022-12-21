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
                'open' => 0,
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
            } else if ($modal['term'] === 'custom' && strtotime($modal['expiry_date']) <= strtotime($modal['start_date'])) {
                $modal['expiry_date_err'] = 'Please select an expiry date that is greater than the start date';
            }

            if (empty($modal['term_err']) && empty($modal['start_date_err']) && empty($modal['expiry_date_err'] )) {
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
                $modal['open'] = 1;
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
                    'open' => 0,
                    'term_err' => '',
                    'start_date_err' => '',
                    'expiry_date_err' => ''
                ],
                'members' => $this->members,
            ];
        }

        $this->view('members/index', $data);
    }

    public function getMembersData() {
        // this is called from an ajax call from the frontend
        $jsonData = $this->members ? json_encode(($this->members)) : '{}';
        echo $jsonData;
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
