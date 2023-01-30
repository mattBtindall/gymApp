<?php
class Member {
    public function __construct() {
        $this->db = new Database();
    }

    public function getMembers() {
        $this->db->query('SELECT user_users.id as user_id, name, email, phone_number, img_url, memberships.expiry_date, memberships.id as membership_id, display_name as term_display_name, cost
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
        $this->db->query('SELECT start_date, expiry_date, memberships.created_at, display_name, cost
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

    public function addMembership($membershipDates, $user_id, $term_id) {
        // admin_id, user_id, term, expiry_date, start_date
        $this->db->query('INSERT INTO memberships (admin_id, user_id, term_id, start_date, expiry_Date) VALUE(:admin_id, :user_id, :termId, :start_date, :expiry_date)');
        $this->db->bind(':admin_id', $_SESSION['user_id']);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':termId', $term_id);
        $this->db->bind(':start_date', $membershipDates['start_date']);
        $this->db->bind(':expiry_date', $membershipDates['expiry_date']);

        return $this->db->execute() ? true : false;
    }

    public function getMemberById($user_id) {
        $this->db->query('SELECT * from memberships WHERE user_id = :userId AND admin_id = :adminId');
        $this->db->bind(':userId', $user_id);
        $this->db->bind(':adminId', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }
}
