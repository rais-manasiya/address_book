<?php
class Group
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM `groups`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching all groups: " . $e->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->db->query("SELECT * FROM groups WHERE id = ?", [$id]);
            $group = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$group) {
                throw new Exception("Group with ID $id not found.");
            }
            return $group;
        } catch (PDOException $e) {
            throw new Exception("Error fetching group by ID: " . $e->getMessage());
        }
    }

    public function create($name)
    {
        try {
            $this->db->query("INSERT INTO groups (name) VALUES (?)", [$name]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error creating group: " . $e->getMessage());
        }
    }

    public function update($id, $name)
    {
        try {
            $stmt = $this->db->query("UPDATE groups SET name = ? WHERE id = ?", [$name, $id]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("Group with ID $id not found.");
            }
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error updating group: " . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();

            // Delete group connections
            $this->db->query("DELETE FROM group_connections WHERE parent_group_id = ? OR child_group_id = ?", [$id, $id]);

            // Delete group contacts
            $this->db->query("DELETE FROM group_contacts WHERE group_id = ?", [$id]);

            // Delete group
            $stmt = $this->db->query("DELETE FROM groups WHERE id = ?", [$id]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("Group with ID $id not found.");
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Error deleting group: " . $e->getMessage());
        }
    }

    public function addContact($groupId, $contactId, $isInherited = false)
    {
        try {
            $sql = "INSERT INTO group_contacts (group_id, contact_id, is_inherited) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE is_inherited = VALUES(is_inherited)";

            $this->db->query($sql, [$groupId, $contactId, (int)$isInherited]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error adding contact to group: " . $e->getMessage());
        }
    }

    public function removeContact($groupId, $contactId)
    {
        try {
            $this->db->query("DELETE FROM group_contacts WHERE group_id = ? AND contact_id = ?", [$groupId, $contactId]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error removing contact from group: " . $e->getMessage());
        }
    }

    public function getContacts($groupId)
    {
        try {
            $sql = "SELECT c.*, gc.is_inherited 
                    FROM contacts c 
                    JOIN group_contacts gc ON c.id = gc.contact_id 
                    WHERE gc.group_id = ?";
            $stmt = $this->db->query($sql, [$groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching group contacts: " . $e->getMessage());
        }
    }

    public function connectGroups($parentGroupId, $childGroupId)
    {
        try {
            $this->db->query("INSERT INTO group_connections (parent_group_id, child_group_id) VALUES (?, ?)", [$parentGroupId, $childGroupId]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error connecting groups: " . $e->getMessage());
        }
    }

    public function disconnectGroups($parentGroupId, $childGroupId)
    {
        try {
            $this->db->query("DELETE FROM group_connections WHERE parent_group_id = ? AND child_group_id = ?", [$parentGroupId, $childGroupId]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error disconnecting groups: " . $e->getMessage());
        }
    }

    public function getConnectedGroups($groupId)
    {
        try {
            $sql = "SELECT g.* 
                    FROM groups g 
                    JOIN group_connections gc ON g.id = gc.child_group_id 
                    WHERE gc.parent_group_id = ?";
            $stmt = $this->db->query($sql, [$groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching connected groups: " . $e->getMessage());
        }
    }

    public function updateInheritedContacts($groupId)
    {
        try {
            $this->db->beginTransaction();

            // Remove all inherited contacts from the group
            $this->db->query("DELETE FROM group_contacts WHERE group_id = ? AND is_inherited = 1", [$groupId]);

            // Get all parent groups
            $parentGroups = $this->getParentGroups($groupId);

            // Add inherited contacts from parent groups
            foreach ($parentGroups as $parentGroup) {
                $parentContacts = $this->getContacts($parentGroup['id']);
                foreach ($parentContacts as $contact) {
                    $this->addContact($groupId, $contact['id'], true);
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Error updating inherited contacts: " . $e->getMessage());
        }
    }

    private function getParentGroups($groupId)
    {
        try {
            $sql = "SELECT g.* 
                    FROM groups g 
                    JOIN group_connections gc ON g.id = gc.parent_group_id 
                    WHERE gc.child_group_id = ?";
            $stmt = $this->db->query($sql, [$groupId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching parent groups: " . $e->getMessage());
        }
    }
}
