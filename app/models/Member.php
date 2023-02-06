<?php
class Member {
    public function __construct() {
        $this->db = new Database();
    }

    public function getMembers() {
        $this->db->query('SELECT user_users.id as user_id, name, email, phone_number, img_url, memberships.expiry_date, memberships.start_date, memberships.id as membership_id, display_name as term_display_name, memberships.cost
                          FROM user_users
                          INNER JOIN memberships
                          ON user_users.id = memberships.user_id
                          INNER JOIN membership_terms
                          ON memberships.term_id = membership_terms.id
                          WHERE memberships.admin_id = :admin_id
                        ');
        $this->db->bind(':admin_id', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function getMostRecentMemberships() {
        // when displaying the members regardless of how many memberships a user has had we only want to show the most recent
        $memberships = $this->getMembers();
        $mostRecentMemberships = [];
        foreach ($memberships as $membership) {
            if (in_array($membership['user_id'], $mostRecentMemberships)) {
                if (strtotime($mostRecentMemberships[$membership['user_id']]['expiry_date']) < strtotime($membership['expiry_date'])) {
                    $mostRecentMemberships[$membership['user_id']] = $membership;
                }
            } else {
                $mostRecentMemberships[$membership['user_id']] = $membership;
            }
        }

        return $mostRecentMemberships;
    }

    public function getTermMembershipByUserId($user_id) {
        $this->db->query('SELECT start_date, expiry_date, memberships.created_at, memberships.id, display_name, memberships.cost
                          FROM memberships
                          INNER JOIN membership_terms
                          ON memberships.term_id = membership_terms.id
                          WHERE memberships.user_id = :userId AND memberships.admin_id = :adminId
                          ORDER BY expiry_date DESC
        ');
        $this->db->bind(':userId', $user_id);
        $this->db->bind(':adminId', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function addMembership($startDate, $expiryDate, $user_id, $term_id, $cost) {
        // admin_id, user_id, term, expiry_date, start_date
        $this->db->query('INSERT INTO memberships (admin_id, user_id, term_id, start_date, expiry_date, cost) VALUE(:admin_id, :user_id, :termId, :start_date, :expiry_date, :cost)');
        $this->db->bind(':admin_id', $_SESSION['user_id']);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':termId', $term_id);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':expiry_date', $expiryDate);
        $this->db->bind(':cost', $cost);

        return $this->db->execute() ? true : false;
    }

    public function deleteMembership($membershipId) {
        $this->db->query('DELETE FROM memberships WHERE id = :membershipId');
        $this->db->bind(':membershipId', $membershipId);
        return $this->db->execute() ? true : false;
    }

    public function logUser($user_id, $admin_id, $active_member) {
        // if the last entry was same as this entries user_id and less than 5 seconds ago don't log the user
        if (!empty($_SESSION['last_insert_id']) && $this->getLastInsertTime($_SESSION['last_insert_id'], $user_id) <= 5) return;
        $this->db->query('INSERT INTO activity (user_id, admin_id, is_active) VALUE(:user_id, :admin_id, :is_active)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':is_active', $active_member);
        $has_logged = $this->db->execute() ? true : false;
        $_SESSION['last_insert_id'] = $this->db->getLastInsertedId();
        return $has_logged;
    }

    private function getLastInsertTime($lastId, $userId) {
        // get the time difference between tthe current insert and the last inserted in seconds
        $this->db->query('SELECT * FROM activity WHERE id = :id AND user_id = :userId');
        $this->db->bind(':id', $lastId);
        $this->db->bind(':userId', $userId);
        $row = $this->db->single(PDO::FETCH_ASSOC);
        if (!$row) return 10;
        $lastInsertTime = date_create_from_format(SQL_DATE_TIME_FORMAT, $row['created_at'])->format(SQL_DATE_TIME_FORMAT);;
        $now = new DateTime();
        return abs(strtotime($now->format(SQL_DATE_TIME_FORMAT)) - strtotime($lastInsertTime));
    }

    public function getMemberById($user_id) {
        $this->db->query('SELECT * from memberships WHERE user_id = :userId AND admin_id = :adminId');
        $this->db->bind(':userId', $user_id);
        $this->db->bind(':adminId', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }
}
