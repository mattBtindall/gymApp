<?php
class Member {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * $area is the database column for the admin_id or user_id
     * gets either all members for a certain admin account
     * or all members for a specific user account determined by $area
     */
    public function getMembersById($area) {
        $this->db->query("SELECT user_users.id as user_id, user_users.name as user_name, user_users.email, user_users.phone_number, user_users.img_url,
                          memberships.expiry_date, memberships.start_date, memberships.id as membership_id, memberships.cost,
                          display_name as term_display_name, term_id,
                          admin_users.name as admin_name, admin_users.id as admin_id
                          FROM user_users
                          INNER JOIN memberships
                          ON user_users.id = memberships.user_id
                          INNER JOIN membership_terms
                          ON memberships.term_id = membership_terms.id
                          INNER JOIN admin_users
                          ON memberships.admin_id = admin_users.id
                          WHERE memberships.{$area} = :{$area}
                          AND memberships.revoked = 0
                        ");
        $this->db->bind(":{$area}", $_SESSION['user_id']);
        return $this->db->resultSet(PDO::FETCH_ASSOC);
    }

    public function getUserRelevantMemberships() {
        $memberships = $this->getMembersById('user_id');
        $memberships = $this->changeKeyName($memberships, 'admin_name', 'name');
        $userId = $memberships[0]['user_id'];

        // split memberships up into admin accounts
        $membershipsByAdminId = [];
        foreach ($memberships as $membership) {
            if (!array_key_exists($membership['admin_id'], $membershipsByAdminId)) {
                $membershipsByAdminId[$membership['admin_id']] = [];
            }
            array_push($membershipsByAdminId[$membership['admin_id']], $membership);
        }

        $relevantMemberships = [];
        foreach ($membershipsByAdminId as $groupedMemberships) {
            $relevantMemberships[] = $this->getRelevantMemberships($groupedMemberships)[$userId];
        }
        $relevantMemberships = $this->changeKeyName($relevantMemberships, 'admin_id', 'user_id'); // so when id is outputted in elements it's for the correct account
        return $relevantMemberships;
    }

    public function getAllRelevantMemberships() {
        // have to change the name of the user_name key to name
        $memberships = $this->getMembersById('admin_id');
        $memberships = $this->changeKeyName($memberships, 'user_name', 'name');
        return $this->getRelevantMemberships($memberships);
    }

    private function changeKeyName($memberships, $oldKeyName, $newKeyName) {
        foreach($memberships as &$membership) {
            $membership[$newKeyName] = $membership[$oldKeyName];
            unset($membership[$oldKeyName]);
        }
        return $memberships;
    }

    private function getRelevantMemberships($memberships) {
        // gets either the active membership or the most recent
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
