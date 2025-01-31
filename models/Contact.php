<?php
class Contact {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    private function validateContactData($data)
    {
        $errors = [];
        if (empty($data['name']) || strlen($data['name']) < 2) {
            $errors[] = "Name must be at least 2 characters long.";
        }
        if (empty($data['first_name']) || strlen($data['first_name']) < 2) {
            $errors[] = "First name must be at least 2 characters long.";
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }
        if (empty($data['street'])) {
            $errors[] = "Street address is required.";
        }
        if (empty($data['zip_code']) || !preg_match('/^\d{5,10}$/', $data['zip_code'])) {
            $errors[] = "Zip code must be between 5 and 10 digits.";
        }
        if (empty($data['city_id']) || !is_numeric($data['city_id'])) {
            $errors[] = "Invalid city selected.";
        }
        return $errors;
    }

    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT c.*, ct.name as city_name FROM contacts c JOIN cities ct ON c.city_id = ct.id");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching all contacts: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->db->query("SELECT * FROM contacts WHERE id = ?", [$id]);
            $contact = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$contact) {
                throw new Exception("Contact with ID $id not found.");
            }
            return $contact;
        } catch (PDOException $e) {
            throw new Exception("Error fetching contact by ID: " . $e->getMessage());
        }
    }
    
    public function create($data) {
        try {
            $errors = $this->validateContactData($data);
            if (!empty($errors)) {
                throw new Exception(implode(" ", $errors));
            }

            $sql = "INSERT INTO contacts (name, first_name, email, street, zip_code, city_id, tags) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $this->db->query($sql, [
                $data['name'],
                $data['first_name'],
                $data['email'],
                $data['street'],
                $data['zip_code'],
                $data['city_id'],
                $data['tags'] ?? ''
            ]);
    
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error creating contact: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $errors = $this->validateContactData($data);
            if (!empty($errors)) {
                throw new Exception(implode(" ", $errors));
            }

            $sql =
            "UPDATE contacts SET 
                    name = ?, first_name = ?, email = ?, 
                    street = ?, zip_code = ?, city_id = ?, tags = ?
                    WHERE id = ?";
            $this->db->query($sql, [
                $data['name'],
                $data['first_name'],
                $data['email'],
                $data['street'],
                $data['zip_code'],
                $data['city_id'],
                $data['tags'] ?? '',
                $id
            ]);
    
            return true;
        } catch (PDOException $e) {
            throw new Exception("Error updating contact: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        try {
            $this->db->beginTransaction();

            // Delete contact from groups
            $this->db->query("DELETE FROM group_contacts WHERE contact_id = ?", [$id]);

            // Delete contact
            $stmt = $this->db->query("DELETE FROM contacts WHERE id = ?", [$id]);
            if ($stmt->rowCount() === 0) {
                throw new Exception("Contact with ID $id does not exist.");
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollback();
            throw new Exception("Error deleting contact: " . $e->getMessage());
        }
    }

    public function getAllTags()
    {
        try {
            $stmt = $this->db->query("SELECT DISTINCT tags FROM contacts WHERE tags != '' ORDER BY tags");
            $tags = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $tags = array_merge($tags, explode(',', $row['tags']));
            }
            return array_unique($tags);
        } catch (PDOException $e) {
            throw new Exception("Error fetching tags: " . $e->getMessage());
        }
    }

    public function getByTag($tag)
    {
        try {
            $stmt = $this->db->query("SELECT c.*, ct.name as city_name FROM contacts c JOIN cities ct ON c.city_id = ct.id WHERE FIND_IN_SET(?, c.tags)", [$tag]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching contacts by tag: " . $e->getMessage());
        }
    }
}