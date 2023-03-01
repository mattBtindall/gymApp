<?php

class Dashboard {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
}
