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

    public function getActiveTerms($adminId) {
        $terms = $this->getTerms($adminId);
        return array_filter($terms, fn($term) => $term['is_active']);
    }

    public function addTerm($term, $adminId) {
        $this->db->query('INSERT INTO membership_terms (display_name, admin_id, term, term_multiplier, cost) VALUE(:displayName, :adminId, :term, :termMultiplier, :cost)');
        $this->db->bind(':displayName', $term['display_name']);
        $this->db->bind(':adminId', $adminId);
        $this->db->bind(':term', $term['term']);
        $this->db->bind(':termMultiplier', $term['term_mulitplier']);
        $this->db->bind(':cost', $term['cost']);
        return $this->db->execute() ? true : false;
    }

    public function deleteTerm($termId) {
        // doesn't actually delete the term as we need a record of it for past memberships
        $this->db->query('UPDATE membership_terms SET is_active = :isActive WHERE id = :termId');
        $this->db->bind(':isActive', '0');
        $this->db->bind(':termId', $termId);
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

    public function getTermById($term_id) {
        $this->db->query('SELECT * FROM membership_terms WHERE id = :id');
        $this->db->bind(':id', $term_id);
        return $this->db->single(PDO::FETCH_ASSOC);
    }
}
