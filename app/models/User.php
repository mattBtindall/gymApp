<?php
    class User {
        public function __construct() {
            $this->db = new Database();
        }

        public function register($data) {
            // Standard query: 'INSERT INTO ' . DB_PREFIX . 'users (name, email, password) VALUE(:name, :email, :password)'

            // create :name (singular values for binding)
            $bindValues = [];
            foreach ($data as $key => $value) {
                $bindValues[$key] = ':' . $key;
            }

            // create (:name, :email, :passowrd)
            $combindedBindValues = implode(", ", $bindValues);

            // create (name, email, password)
            $values = str_replace( ':', '', $combindedBindValues);

            $this->db->query('INSERT INTO ' . strtolower(AREA) . '_users (' . $values . ') VALUE(' . $combindedBindValues . ')');

            // bind values  $this->db->bind(':name', $data['name']);
            foreach($bindValues as $key => $value) {
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

        public function userExists($email) {
            $this->db->query('SELECT * FROM ' . strtolower(AREA) . '_users WHERE email = :email');
            $this->db->bind(':email', $email);
            return $this->db->single() ? true : false;
        }

        public function uploadImg($imgUrl) {
            // Get the user by id 
            
        }

        public function selectUserById($id, $area = AREA) { // can pass area in so it can be use with is admin below
            $this->db->query('SELECT * FROM ' . strtolower($area) . '_users WHERE id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        }

        public function isAdmin() {
            return $this->selectUserById($_SESSION['user_id'], 'admin') ? true : false;
        }

       
    }
