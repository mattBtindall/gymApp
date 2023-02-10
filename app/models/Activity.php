<?php
class Activity {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMembersActivity($admin_id) {
        $this->db->query('SELECT * FROM activity WHERE admin_id = :adminId ORDER BY created_at DESC');
        $this->db->bind(':adminId', $admin_id);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function getMemberActivity($admin_id, $user_id) {
        $activity = $this->getMembersActivity($admin_id);
        return array_filter($activity, fn($val) => $val['user_id'] == $user_id);
    }

    public function getActivityById($id) {
        $this->db->query('SELECT * FROM activity WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single(PDO::FETCH_ASSOC);
    }

    public function logUser($user_id, $admin_id, $active_member) {
        // if the last entry was same as this entries user_id and less than 5 seconds ago don't log the user
        if (!empty($_SESSION['last_insert_id']) && $this->getLastInsertTime($_SESSION['last_insert_id'], $user_id) <= 5) return;
        $this->db->query('INSERT INTO activity (user_id, admin_id, is_active) VALUE(:user_id, :admin_id, :is_active)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':admin_id', $admin_id);
        $this->db->bind(':is_active', $active_member);
        if ($this->db->execute()) {
            $_SESSION['last_insert_id'] = $this->db->getLastInsertedId();
            return $this->getActivityById($_SESSION['last_insert_id']);
        }
        return false;
    }

    private function getLastInsertTime($lastId, $userId) {
        // get the time difference between tthe current insert and the last inserted in seconds
        $this->db->query('SELECT * FROM activity WHERE id = :id AND user_id = :userId');
        $this->db->bind(':id', $lastId);
        $this->db->bind(':userId', $userId);
        $row = $this->db->single(PDO::FETCH_ASSOC);
        if (!$row) return 10;
        $lastInsertTime = DateTime::createFromFormat(SQL_DATE_TIME_FORMAT, $row['created_at'])->format(SQL_DATE_TIME_FORMAT);;
        $now = new DateTime();
        return abs(strtotime($now->format(SQL_DATE_TIME_FORMAT)) - strtotime($lastInsertTime));
    }
}
