<?php
class Term {
    public function __construct() {
        $this->db = new Database();
    }

    public function getTerms($adminId) {
        $this->db->query('SELECT * FROM membership_terms WHERE admin_id = :adminId');
        $this->db->bind(':adminId', $adminId);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function deleteTerm($termId) {
        $this->db->query('DELETE FROM membership_terms WHERE id = :id');
        $this->db->bind(':id', $termId);
        return $this->db->execute() ? true : false;
    }
}