<?php
class Activity {
    public function __construct() {
        $this->db = new Database();
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
}