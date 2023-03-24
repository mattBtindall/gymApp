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
}
