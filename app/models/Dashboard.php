<?php

class Dashboard {
    private $db;

    /**
     * the amount of points on the revenue chart
     * this is how many segmenets we need when calculating the revenue
     */
    const REVENUE_POINTS = 4;

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

    private function getMembershipsInTimeFrame($date, $adminId) {
        // timeFrame can be: '1 week', '4 week', '3 month', '6 month', '12 month'
        $this->db->query("SELECT cost, created_at
                          from memberships
                          WHERE admin_id = :adminId
                          AND created_at > :dateValue");
        $this->db->bind(':adminId', $adminId);
        $this->db->bind(':dateValue', $date);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    private function getQuateredTimeFrame($date) {
        $formattedDate = $date->format(SQL_DATE_TIME_FORMAT);
        // ceil becuase we want to get rid of decimals and it doesn't matter if the time is a bit later today
        // as memberships can't be created in the future
        $timeDifferenceHours = (strtotime('now') - strtotime($formattedDate))/60/60;
        $timeDifferenceQuarter = ceil($timeDifferenceHours / self::REVENUE_POINTS);
        $dates = [];
        for ($i = 0; $i < self::REVENUE_POINTS; $i++) {
            $dates[] = [
                'oldest' => $date->format(SQL_DATE_TIME_FORMAT),
                'newest' => $date->modify('+' . $timeDifferenceQuarter . ' hours')->format(SQL_DATE_TIME_FORMAT)
            ];
        }
        return $dates;
    }

    public function getRevenue($timeFrame, $adminId) {
        $now = new DateTime();
        $date = $now->modify('-' . $timeFrame . 's 00:00:00');
        $memberships = $this->getMembershipsInTimeFrame($date->format(SQL_DATE_TIME_FORMAT), $adminId);
        $quarteredTimeFrames = $this->getQuateredTimeFrame($date);
        $quarteredMemberships = [];
        $quarteredRevenues = [];

        foreach ($quarteredTimeFrames as $qtf) {
            $quarteredMemberships[] = array_filter($memberships, function($membership) use($qtf) {
                if (strtotime($membership['created_at']) > strtotime($qtf['oldest']) && strtotime($membership['created_at']) < strtotime($qtf['newest'])) {
                    return true;
                }
            });
        }

        foreach($quarteredMemberships as $quarteredMembership) {
            $quarteredRevenues[] = array_reduce($quarteredMembership, fn($accumulator, $currentValue) => $accumulator + $currentValue['cost'], 0);
        }
        return $quarteredRevenues;
    }
}
