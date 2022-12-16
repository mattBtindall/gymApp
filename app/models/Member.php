<?php
class Member {
    public function __construct() {
        $this->db = new Database();
    }

    public function getMembers($admin_id) {
        $this->db->query('SELECT user_users.id, name, email, phone_number, term, expiry_date, img_url
                          FROM user_users
                          INNER JOIN memberships
                          ON memberships.user_id = user_users.id
                          WHERE memberships.admin_id = :admin_id
                        ');
        $this->db->bind(':admin_id', $admin_id);
        $results = $this->db->resultSet(PDO::FETCH_ASSOC);
        return $results ? $results : '';
    }

    public function addMembership($membershipDates, $user_id) {
        // admin_id, user_id, term, expiry_date, start_date
        $this->db->query('INSERT INTO memberships (admin_id, user_id, term, start_date, expiry_Date) VALUE(:admin_id, :user_id, :term, :start_date, :expiry_date)');
        $this->db->bind('admin_id', $_SESSION['user_id']);
        $this->db->bind('user_id', $user_id);
        $this->db->bind('term', $membershipDates['term']);
        $this->db->bind(':start_date', $membershipDates['start_date']);
        $this->db->bind(':expiry_date', $membershipDates['expiry_date']);

        return $this->db->execute() ? true : false;
    }
}
