<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        $singleBindVals = $this->createValues($data);

        // combindedBindValues = '(:name, :email, :passowrd)'
        $combindedBindVals = implode(", ", $singleBindVals);

        // values = '(name, email, password)'
        $listVals = str_replace( ':', '', $combindedBindVals);

        $this->db->query('INSERT INTO ' . strtolower(AREA) . '_users (' . $listVals . ') VALUE(' . $combindedBindVals . ')');

        // bind values  $this->db->bind(':name', $data['name']);
        foreach($singleBindVals as $key => $value) {
            $this->db->bind($value, $data[$key]);
        }

        return $this->db->execute() ? true : false;
    }

    public function login($email, $password) {
        $this->db->query('SELECT * FROM ' . strtolower(AREA) . '_users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        $hashed_password = $row->password;

        return (password_verify($password, $hashed_password)) ? $row : false;
    }

    public function uploadImg($imgUrl, $id) {
        $this->db->query('UPDATE ' . strtolower(AREA) . '_users SET img_url = :imgUrl WHERE id = :id');
        $this->db->bind(':imgUrl', $imgUrl);
        $this->db->bind(':id', $id);
        return $this->db->execute() ? true : false;
    }

    public function updateUser($data, $id) {
        // $singleBindVals = [':name',':eamil',':password']
        $singleBindVals = $this->createValues($data);

        // $combinedBindVals = 'title = :title, body = :body'
        $combindedBindVals = '';
        foreach ($singleBindVals as $key => $value) {
            $combindedBindVals .= str_replace(':', '', $value) . ' = ' . $value;
            if ($key !== array_key_last($singleBindVals)) {
                $combindedBindVals .= ', ';
            }
        }

        $this->db->query('UPDATE ' . strtolower(AREA) . '_users SET ' . $combindedBindVals . ' WHERE id = :id');
        foreach($singleBindVals as $key => $value) {
            $this->db->bind($value, $data[$key]);
        }
        $this->db->bind(':id', $id);

        return $this->db->execute() ? true : false;
    }

    public function createValues($data) {
        // $singleBindVals = [':name',':eamil',':password']
        $singleBindVals = [];
        foreach ($data as $key => $value) {
            $singleBindVals[$key] = ':' . $key;
        }

        return $singleBindVals;
    }

    public function userExists($email, $area = AREA) {
        $this->db->query('SELECT * FROM ' . strtolower($area) . '_users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single() ? true : false;
    }

    public function selectUserById($id, $area = AREA) { // can pass area in so it can be use with is admin below
        $this->db->query('SELECT * FROM ' . strtolower($area) . '_users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single(PDO::FETCH_ASSOC);
    }

    public function emailExists($email) {
        $adminSide = $this->userExists($email, 'admin');
        $userSide = $this->userExists($email, 'user');
        return $this->userExists($email, 'admin') || $this->userExists($email, 'user');
    }

    public function selectUserBySearchQuery($searchQuery, $area) {
        // select everything but password
        $this->db->query('SELECT id, name, email, phone_number, img_url FROM ' . $area . '_users WHERE name LIKE :searchQuery');
        $this->db->bind(':searchQuery', $searchQuery . '%');
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function isAdmin() {
        return $this->selectUserById($_SESSION['user_id'], 'admin') ? true : false;
    }

    public function getAllUsersFromOppositeArea() {
        $oppositeArea = strtolower(getOppositeArea());
        $this->db->query('SELECT * FROM ' . $oppositeArea . '_users');
        $rows = $this->db->resultSet(PDO::FETCH_ASSOC);
        return $rows;
    }
}
