<?php
class Member {
    public function __construct() {
        $this->db = new Database();
    }

    public function getMembers() {
        // change this so that is more generic so it returns the results and then at source return the '{}' for when working with json
        // $this->db->query('SELECT user_users.id, memberships.id, name, email, phone_number, expiry_date, img_url,
        //                   user_users.id as user_id,
        //                   memberships.id as membership_id
        //                   FROM user_users
        //                   INNER JOIN memberships
        //                   ON memberships.user_id = user_users.id
        //                   WHERE memberships.admin_id = :admin_id
        //                 ');
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

    public function addMembership($membershipDates, $user_id) {
        // admin_id, user_id, term, expiry_date, start_date
        $this->db->query('INSERT INTO memberships (admin_id, user_id, term, start_date, expiry_Date) VALUE(:admin_id, :user_id, :term, :start_date, :expiry_date)');
        $this->db->bind(':admin_id', $_SESSION['user_id']);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':term', $membershipDates['term']);
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
