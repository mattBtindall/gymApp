<?php
class Members extends Controller {
    public function __construct() {
        $this->membersModel = $this->model('Member');
        $this->userModel = $this->model('User');
        $this->memberships = $this->membersModel->getMembers($_SESSION['user_id']);
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
            }
            else if ($this->dateOverlap($modal['user_id'], $modal['start_date'])) {
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
                    $this->memberships = $this->membersModel->getMembers($_SESSION['user_id']); // reload members so newly added member shows
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
                'members' => $this->memberships,
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
                'members' => $this->memberships,
            ];
        }

        $mostRecentMemberships = $this->getMostRecentMemberships($this->memberships);
        $this->view('members/index', $data);
    }

    public function activity() {
        // get activity for this account


        $this->view('members/activity');
    }

    public function getMembersData() {
        // this is called from an ajax call from the frontend
        $jsonData = $this->memberships ? json_encode(($this->memberships)) : '{}';
        echo $jsonData;
    }

    private function getMostRecentMemberships($memberships) {
        // when displaying the members regardless of how many memberships a user has had we only want to show the most recent
        $mostRecentMemberships = [];
        foreach ($memberships as $membership) {
            if (in_array($membership['user_id'], $mostRecentMemberships)) {
                // if (strtotime($mostRecentMemberships[$membership['user_id']]['expiry_date']) < strtotime($membership['expiry_date'])) {
                if (strtotime($mostRecentMemberships[$membership['user_id']]['expiry_date']) < strtotime($membership['expiry_date'])) {
                    $mostRecentMemberships[$membership['user_id']] = $membership;
                }
            } else {
                $mostRecentMemberships[$membership['user_id']] = $membership;
            }
        }

        foreach($mostRecentMemberships as $recentMembership) {
            var_dump($recentMembership);
            echo '<br>';
        }
        return $mostRecentMemberships;
    }

    private function dateOverlap($user_id, $startDate) {
        // get active memberships from the user
        $memberships = $this->membersModel->getMemberById($user_id);
        if (!$memberships) return false;

        $hasOverlap = false;
        // date here comes in from html so first need to create a date using html dateTime format and then convert it to SQL dateTime format so they can be compared
        $startDate = date_create_from_format(HTML_DATE_TIME_FORMAT, $startDate);
        $startDate = $startDate->format(SQL_DATE_TIME_FORMAT);

        foreach ($memberships as $membership) {
            // date here comes from db so the date object can be created straight from sql dateTime format
            $expiryDate = date_create_from_format(SQL_DATE_TIME_FORMAT, $membership['expiry_date']);
            $expiryDate = $expiryDate->format(SQL_DATE_TIME_FORMAT); // need to use this to get the date string

            if (strtotime($startDate) < strtotime($expiryDate)) {
                $hasOverlap = true;
                break;
            }
        }

        return $hasOverlap;
    }

    private function generateMembershipDates($term, $startDate, $endDate) {
        // notice here that when the date comes from html the HTML_DATE_TIME_FORMAT is used
        // the same goes for when the date comes from the SQL db -> SQL_DATE_TIME_FORMAT is used
        if (!$endDate) {
            $endDate = date_create_from_format(HTML_DATE_TIME_FORMAT, $startDate);
            $endDate->modify('+' . $term . ' month');
        } else {
            $endDate = date_create_from_format(HTML_DATE_TIME_FORMAT, $endDate);
        }
        $startDate = date_create_from_format(HTML_DATE_TIME_FORMAT, $startDate);

        return [
            'term' => $term,
            'start_date' => $startDate->format(SQL_DATE_TIME_FORMAT),
            'expiry_date' => $endDate->format(SQL_DATE_TIME_FORMAT)
        ];
    }
}
