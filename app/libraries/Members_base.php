<?php
class Members_base extends Controller {
    private $mostRecentMemberships;

    public function __construct($relevantMemberships) {
        $this->mostRecentMemberships = $relevantMemberships;
    }

    public function index() {
        $data = [
            'modal' => [
                'user_id' => 0,
                'term_id' => '',
                'start_date' => '',
                'expiry_date' => '',
                'cost' => '',
                'term_id_err' => '',
                'start_date_err' => '',
                'expiry_date_err' => '',
                'cost_err' => ''
            ],
        ];

        $data['members'] = $this->mostRecentMemberships;
        $this->view('members/index', $data);
    }

    public function getTermMembershipByUserId($user_id) {
        $membershipTerms = $this->membersModel->getTermMembershipByUserId($user_id);
        foreach ($membershipTerms as &$memberTerm) {
            // + 1 day becuase the comparison includes time so if its on the same day we don't want it to have expired
            $modifiedDate = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $memberTerm['expiry_date'])->modify('+ 1 day');
            $memberTerm['is_expired'] = $modifiedDate < new DateTime('now');
            $memberTerm['status'] = $memberTerm['revoked'] ? 'revoked' : getMembershipStatus($memberTerm['start_date'], $memberTerm['expiry_date']);
            $memberTerm['cost'] = convertNumberToPrice($memberTerm['cost']);
            $memberTerm['expiry_date'] = formatForOutput($memberTerm['expiry_date']);
            $memberTerm['start_date'] = formatForOutput($memberTerm['start_date']);
            $memberTerm['created_at'] = formatForOutput($memberTerm['created_at']);
        }

        $formattedData = !empty($membershipTerms) ? $membershipTerms : '{}';
        echo json_encode($formattedData);
    }

}
