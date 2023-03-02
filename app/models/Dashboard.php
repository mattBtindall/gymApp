<?php

class Dashboard {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getNumberOfActiveMembers($adminId) {
        $this->db->query('SELECT * FROM memberships WHERE admin_id = :adminId AND revoked = 0');
        $this->db->bind(':adminId', $adminId);
        $memberships = $this->db->resultSet(PDO::FETCH_ASSOC);

        $numberOfAactiveMembers = 0;
        if ($memberships) {
            foreach($memberships as $membership) {
                if (getMembershipStatus($membership['start_date'], $membership['expiry_date']) === 'active') $numberOfAactiveMembers++;
            }
        }
        return $numberOfAactiveMembers;
    }

    public function getNumberOfVisits($adminId, $furthestDate, $closestDate) {
        $this->db->query("SELECT * FROM activity WHERE created_at >= '{$furthestDate}' AND created_at <= '{$closestDate}' AND admin_id = :adminId AND membership_status = 'active'");
        $this->db->bind(':adminId', $adminId);
        $activity = $this->db->resultSet(PDO::FETCH_ASSOC);
        return count($activity);
    }
}
