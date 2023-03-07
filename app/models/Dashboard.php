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

    public function getRecentMembers($adminId) {
        $now = new DateTime();
        $monthAgo = $now->modify('-1 month 00:00:00')->format(SQL_DATE_TIME_FORMAT);
        $this->db->query("SELECT memberships.created_at, memberships.expiry_date, user_users.img_url, user_users.name, user_users.email, user_users.id as user_id
                          FROM memberships
                          INNER JOIN user_users
                          ON memberships.user_id = user_users.id
                          WHERE memberships.created_at >= :monthAgo
                          AND memberships.admin_id = :adminId
                          AND memberships.revoked = 0
                          AND memberships.expiry_date > :today
                          ORDER BY memberships.created_at DESC");
        $this->db->bind(':monthAgo', $monthAgo);
        $this->db->bind(':adminId', $adminId);
        $this->db->bind(':today', $now->modify('+1 month 00:00:00')->format(SQL_DATE_TIME_FORMAT));
        echo $now->format(SQL_DATE_TIME_FORMAT);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
        // user_users.img_url, user_users.name, user_users.email, user_users.id, memberships.created_at [use this to see how long ago the membership was created],
    }
}
