<?php
class Activity {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMembersActivity($admin_id) {
        $this->db->query('SELECT * FROM activity WHERE admin_id = :adminId ORDER BY created_at ASC');
        $this->db->bind(':adminId', $admin_id);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function getMemberActivity($admin_id, $user_id) {
        $activity = $this->getMembersActivity($admin_id);
        return array_filter($activity, fn($val) => $val['user_id'] == $user_id);
    }

    public function getMembersActivityUser($user_id, $date = "NOW()") {
        $activity = $this->getAllActivity($user_id, 'user_id', $date);
        $activity = changeKeyName($activity, 'admin_name', 'name');
        $activity = changeKeyName($activity, 'admin_id', 'user_id');
        return $activity;
    }

    public function getMembersActivityAdmin($admin_id, $date = "NOW()") {
        $activity = $this->getAllActivity($admin_id, 'admin_id', $date);
        $activity = changeKeyName($activity, 'user_name', 'name');
        return $activity;
    }

    public function getAllActivity($admin_id, $areaType, $date = "NOW()") {
        // $date = the date to get the activity for, this must be wrapped in single quotes e.g. "'2023-02-08'"
        $this->db->query("SELECT membership_status,
                          activity.created_at, display_name as term_display_name,
                          start_date as membership_start_date, expiry_date as membership_expiry_date,
                          user_users.name as user_name, user_users.img_url, user_users.id as user_id,
                          admin_users.name as admin_name, admin_users.id as admin_id
                          FROM activity
                          INNER JOIN membership_terms
                          ON activity.term_id = membership_terms.id
                          INNER JOIN memberships
                          ON activity.membership_id = memberships.id
                          INNER JOIN user_users
                          ON activity.user_id = user_users.id
                          INNER JOIN admin_users
                          ON activity.admin_id = admin_users.id
                          WHERE activity.{$areaType}= :{$areaType}
                          AND DATE(activity.created_at) = DATE({$date})
                          ORDER BY activity.created_at DESC");
        $this->db->bind(":{$areaType}", $admin_id);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function getActivityById($id) {
        $this->db->query('SELECT membership_status, activity.created_at, display_name as term_display_name, start_date as membership_start_date, expiry_date as membership_expiry_date, name, img_url, user_users.id as user_id
                          FROM activity
                          INNER JOIN membership_terms
                          ON activity.term_id = membership_terms.id
                          INNER JOIN memberships
                          ON activity.membership_id = memberships.id
                          INNER JOIN user_users
                          ON activity.user_id = user_users.id
                          WHERE activity.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single(PDO::FETCH_ASSOC);
    }

    public function logUser($data) {
        // in here instead of storing the membership date within the table, simple store the membership id and then
        // when getting the data do a simple join on the membership table
        // if the last entry was same as this entries user_id and less than 5 seconds ago don't log the user
        if (!empty($_SESSION['last_insert_id']) && $this->getLastInsertTime($_SESSION['last_insert_id'], $data['user_id']) <= 5) return;
        $this->db->query('INSERT INTO activity (user_id, admin_id, membership_status, term_id, membership_id) VALUE(:user_id, :admin_id, :membership_status, :term_id, :membership_id)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':admin_id', $data['admin_id']);
        $this->db->bind(':membership_status', $data['status']);
        $this->db->bind(':term_id', $data['term_id']);
        $this->db->bind(':membership_id', $data['membership_id']);
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
