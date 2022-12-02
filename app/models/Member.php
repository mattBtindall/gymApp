<?php
class Member {
    public function __construct() {
        $this->db = new Database();
    }

    public function getMembers($admin_id) {
        $this->db->query('SELECT name, email, phone_number, term, expiry_date, img_url
                          FROM user_users
                          INNER JOIN memberships
                          ON memberships.user_id = user_users.id
                          WHERE memberships.admin_id = :admin_id
                        ');
        $this->db->bind(':admin_id', $admin_id);
        $results = $this->db->resultSet(PDO::FETCH_ASSOC);
        return $results ? $results : '';
    }
}
