<?php
    /*
     * Example Model
     * This shouldn't be included in the framework
     * It is included here as an example 
     */

    class Post {
        private $db;

        public function __construct() {
            $this->db = new Database();
        }

        public function getPosts() {
            $this->db->query('SELECT * FROM post_test');
            return $this->db->resultSet();
        }
    }