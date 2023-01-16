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

    public function editTerm($term) {
        $this->db->query('UPDATE membership_terms SET display_name = :displayName, term = :term, term_multiplier = :termMultiplier, cost = :cost WHERE id = :termId');
        $this->db->bind(':displayName', $term['display_name']);
        $this->db->bind(':term', $term['term']);
        $this->db->bind(':termMultiplier', $term['term_multiplier']);
        $this->db->bind(':cost', $term['cost']);
        $this->db->bind(':termId', $term['term_id']);
        return $this->db->execute() ? true : false;
    }
}
