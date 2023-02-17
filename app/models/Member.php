<?php
class Member {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getMembers() {
        /* Gets all NON REVOKED memberships for the current admin account */
        $this->db->query('SELECT user_users.id as user_id, name, email, phone_number, img_url, memberships.expiry_date, memberships.start_date, memberships.id as membership_id, display_name as term_display_name, memberships.cost
                          FROM user_users
                          INNER JOIN memberships
                          ON user_users.id = memberships.user_id
                          INNER JOIN membership_terms
                          ON memberships.term_id = membership_terms.id
                          WHERE memberships.admin_id = :admin_id
                          AND memberships.revoked = 0
                        ');
        $this->db->bind(':admin_id', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function getRelevantMemberships() {
        // gets either the active membership or the most recent
        $memberships = $this->getMembers();
        $mostRecentMemberships = [];
        $excludeIds = [];
        foreach ($memberships as &$membership) {
            if (in_array($membership['user_id'], $excludeIds)) continue;

            if (getMembershipStatus($membership['start_date'], $membership['expiry_date']) === "active") {
                $mostRecentMemberships[$membership['user_id']] = $membership;
                array_push($excludeIds, $membership['user_id']);
                continue;
            }

            if (in_array($membership['user_id'], $mostRecentMemberships)) {
                if (strtotime($mostRecentMemberships[$membership['user_id']]['expiry_date']) < strtotime($membership['expiry_date'])) {
                    $mostRecentMemberships[$membership['user_id']] = $membership;
                }
            } else {
                $mostRecentMemberships[$membership['user_id']] = $membership;
            }
        }

        return $mostRecentMemberships;
    }

    public function getTermMembershipByUserId($user_id) {
        $this->db->query('SELECT start_date, expiry_date, memberships.created_at, memberships.id, revoked, display_name, memberships.cost
                          FROM memberships
                          INNER JOIN membership_terms
                          ON memberships.term_id = membership_terms.id
                          WHERE memberships.user_id = :userId AND memberships.admin_id = :adminId
                          ORDER BY expiry_date DESC
        ');
        $this->db->bind(':userId', $user_id);
        $this->db->bind(':adminId', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function addMembership($startDate, $expiryDate, $user_id, $term_id, $cost) {
        $this->db->query('INSERT INTO memberships (admin_id, user_id, term_id, start_date, expiry_date, cost) VALUE(:admin_id, :user_id, :termId, :start_date, :expiry_date, :cost)');
        $this->db->bind(':admin_id', $_SESSION['user_id']);
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':termId', $term_id);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':expiry_date', $expiryDate);
        $this->db->bind(':cost', $cost);

        return $this->db->execute() ? true : false;
    }

    public function revokeMembership($membershipId) {
        $this->db->query('UPDATE memberships SET revoked = 1 WHERE id = :membershipId');
        $this->db->bind(':membershipId', $membershipId);
        return $this->db->execute() ? true : false;
    }

    public function getMemberById($user_id) {
        $this->db->query('SELECT * from memberships WHERE user_id = :userId AND admin_id = :adminId AND revoked = 0');
        $this->db->bind(':userId', $user_id);
        $this->db->bind(':adminId', $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }
}
