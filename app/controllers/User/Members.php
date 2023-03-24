<?php
class Members extends Members_base {
    public function __construct() {
        $this->membersModel = $this->model('Member');
        parent::__construct($this->membersModel->getUserRelevantMemberships());
    }
}
