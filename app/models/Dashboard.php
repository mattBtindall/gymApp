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

    public function getExpiringMembers($adminId) {
        return $this->getMembersByTimeFrame($adminId, '+2 days 00:00:00 ', 'memberships.expiry_date <= :timeCondition');
    }

    public function getRecentMembers($adminId) {
        return $this->getMembersByTimeFrame($adminId, '-1 month 00:00:00', 'memberships.created_at >= :timeCondition');
    }

    private function getMembersByTimeFrame($adminId, $timeAdjustment, $condition) {
        $now = new DateTime();
        $timeCondition = $now->modify($timeAdjustment)->format(SQL_DATE_TIME_FORMAT);
        $this->db->query("SELECT memberships.created_at, memberships.expiry_date, user_users.img_url, user_users.name, user_users.email, user_users.id as user_id
                          FROM memberships
                          INNER JOIN user_users
                          ON memberships.user_id = user_users.id
                          WHERE $condition
                          AND memberships.admin_id = :adminId
                          AND memberships.revoked = 0
                          AND memberships.expiry_date >= :today
                          ORDER BY memberships.created_at DESC");
        $this->db->bind(':timeCondition', $timeCondition);
        $this->db->bind(':adminId', $adminId);
        $this->db->bind(':today', $now->modify(date('Y-m-d'))->format(SQL_DATE_TIME_FORMAT));
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }
}
