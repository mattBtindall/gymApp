<?php
class Members extends Members_base {
    protected $membersModel;
    private $userModel;
    private $termModel;
    private $memberships;

    public function __construct() {
        $this->membersModel = $this->model('Member');
        $this->userModel = $this->model('User');
        $this->termModel = $this->model('Term');
        $this->memberships = $this->membersModel->getMembersById('admin_id');
        parent::__construct($this->membersModel->getAllRelevantMemberships());
    }

    public function addMembership() {
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        $modal = [
            'user_id' => trim($_POST['user_id']),
            'term_id' => trim($_POST['term_id']),
            'start_date' => trim($_POST['start_date']),
            'expiry_date' => trim($_POST['expiry_date']),
            'cost' => trim($_POST['cost']),
            'term_length' => '',
            'term_id_err' => '',
            'start_date_err' => '',
            'expiry_date_err' => '',
            'cost_err' => ''
        ];

        // before error checking need to get expiry date as it's not passed from form when term !== custom
        // in order to get the expiry date we need to get the term length
        if ($modal['term_id'] !== 'custom') {
            $term = $this->termModel->getTermById($modal['term_id']);
            $modal['term_length'] = $term['term_multiplier'] . ' ' . $term['term'];
            $modal['expiry_date'] = DateTime::createFromFormat(HTML_DATE_TIME_FORMAT, $modal['start_date'])->modify('+' . $modal['term_length']);
        } else {
            $modal['expiry_date'] = DateTime::createFromFormat(HTML_DATE_TIME_FORMAT, $modal['expiry_date']);
        }

        if (empty($modal['term_id']) || $modal['term_id'] === 'please_select') {
            $modal['term_id_err'] = 'Please select a term.';
        }

        if (empty($modal['start_date'])) {
            $modal['start_date_err'] = 'Please select a start date.';
        }
        else if ($this->dateOverlap($modal['user_id'], $modal['start_date'], $modal['expiry_date'])) {
            $modal['start_date_err'] = 'Please select a date that begins after the current membership expires';
        }

        if ($modal['term_id'] === 'custom' && empty($modal['expiry_date'])) {
            $modal['expiry_date_err'] = 'Please select an expiry date.';
        } else if ($modal['term_id'] === 'custom' && strtotime($modal['expiry_date']) <= strtotime($modal['start_date'])) {
            $modal['expiry_date_err'] = 'Please select an expiry date that is greater than the start date';
        }

        if (empty($modal['cost'])) {
            $modal['cost_err'] = 'Please select a cost.';
        }

        // format dats before passing to db & modals.php for output, resets time as we don't need this when comparing memberships
        $modal['expiry_date'] = $modal['expiry_date']->setTime(0, 0, 0)->format(SQL_DATE_TIME_FORMAT);
        $modal['start_date'] = DateTime::createFromFormat(HTML_DATE_TIME_FORMAT, $modal['start_date'])->setTime(0, 0, 0)->format(SQL_DATE_TIME_FORMAT);

        if (empty($modal['term_id_err']) && empty($modal['start_date_err']) && empty($modal['expiry_date_err']) && empty($modal['cost_err'])) {
            // reset modal state so that it doesn't reopen
            $_SESSION['user_modal_state']['open'] = false;
            $_SESSION['user_modal_state']['user_id'] = 0;
            $_SESSION['user_modal_state']['selected'] = '';

            // create new trerm if the user has seleted custom dates
            if ($modal['term_id'] === 'custom') {
                $term = [
                    'display_name' => 'custom',
                    'term' => 'n/a',
                    'term_multiplier' => '0',
                    'cost' => $modal['cost']
                ];
                $modal['term_id'] = $this->termModel->addTerm($term, $_SESSION['user_id'], '1');
            }

            if ($this->membersModel->addMembership($modal['start_date'], $modal['expiry_date'], $modal['user_id'], $modal['term_id'], $modal['cost'])) {
                $userName = $this->userModel->selectUserById($modal['user_id'], 'User')['name'];
                $successMsg = "A membership has been successfully added for {$userName}";
                flash('membership_assignment', $successMsg);
            } else {
                flash('membership_assignment', 'Membership assignment failed', 'alert alert-danger');
            }
        } else {
            // errors so tell javascript to open modal
            $_SESSION['user_modal_state']['open'] = true;
            $_SESSION['user_modal_state']['user_id'] = $modal['user_id'];
            $_SESSION['user_modal_state']['selected'] = $modal['term_id'];
        }

        $data = [
            'modal' => $modal,
        ];

        $mostRecentMemberships = $this->membersModel->getAllRelevantMemberships();
        $data['members'] = $mostRecentMemberships;
        $this->view('members/index', $data);
    }

    public function revokeMembership($membershipId) {
        if ($this->membersModel->revokeMembership($membershipId)) {
            flash('membership_revoked', 'Membership revoked successfully');
        } else {
            flash('membership_revoked', 'Failed to revoke membership, please try again', 'alert alert-danger');
        }
        redirect('/members/index');
    }

    public function getMembersData() {
        // this is called from an ajax call from the frontend
        $jsonData = $this->memberships ? json_encode(($this->memberships)) : '{}';
        echo $jsonData;
    }

    private function dateOverlap($user_id, $newStartDate, $newExpiryDate) {
        // get active memberships from the user
        $memberships = $this->membersModel->getMemberById($user_id);
        if (!$memberships) return false;

        if (!$newStartDate instanceof DateTime) $newStartDate = DateTime::createFromFormat(HTML_DATE_TIME_FORMAT, $newStartDate);

        foreach ($memberships as $membership) {
            $startDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $membership['start_date']);
            $expiryDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $membership['expiry_date'])->modify('+1 day');

            // covers start date being in between $membership dates
            // start and expiryt date going around $membership dates
            // expiry date between inn between $membership dates
            if ($newStartDate >= $startDate && $newStartDate <= $expiryDate
                || $newStartDate < $startDate && $newExpiryDate > $expiryDate
                || $newExpiryDate >= $startDate && $newExpiryDate < $expiryDate)
            {
                return true;
            }
        }

        return false;
    }
}
