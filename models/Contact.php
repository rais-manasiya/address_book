<?php
class Contact {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        try {
            $result = $this->db->query("SELECT c.*, ct.name as city_name FROM contacts c JOIN cities ct ON c.city_id = ct.id");
            if (!$result) {
                throw new Exception("Query failed: " . $this->db->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error fetching all contacts: " . $e->getMessage());
        }
    }

    public function getById($id) {
        try {
            $id = $this->db->escape($id);
            $result = $this->db->query("SELECT * FROM contacts WHERE id = $id");
    
            if (!$result) {
                throw new Exception("Query failed: " . $this->db->error);
            }
    
            $contact = $result->fetch_assoc();
            if (!$contact) {
                throw new Exception("Contact with ID $id not found.");
            }
    
            return $contact;
        } catch (Exception $e) {
            throw new Exception("Error fetching contact by ID: " . $e->getMessage());
        }
    }
    
    public function create($data) {
        try {
            $name = $this->db->escape($data['name']);
            $first_name = $this->db->escape($data['first_name']);
            $email = $this->db->escape($data['email']);
            $street = $this->db->escape($data['street']);
            $zip_code = $this->db->escape($data['zip_code']);
            $city_id = $this->db->escape($data['city_id']);
            $sql = "INSERT INTO contacts (name, first_name, email, street, zip_code, city_id) 
                    VALUES ('$name', '$first_name', '$email', '$street', '$zip_code', $city_id)";
    
            if (!$this->db->query($sql)) {
                throw new Exception("Insert failed: " . $this->db->error);
            }
    
            return true;
        } catch (Exception $e) {
            throw new Exception("Error creating contact: " . $e->getMessage());
        }
    }

    public function update($id, $data) {
        try {
            $id = $this->db->escape($id);
            $name = $this->db->escape($data['name']);
            $first_name = $this->db->escape($data['first_name']);
            $email = $this->db->escape($data['email']);
            $street = $this->db->escape($data['street']);
            $zip_code = $this->db->escape($data['zip_code']);
            $city_id = $this->db->escape($data['city_id']);
    
            $sql = "UPDATE contacts SET 
                    name = '$name', 
                    first_name = '$first_name', 
                    email = '$email', 
                    street = '$street', 
                    zip_code = '$zip_code', 
                    city_id = $city_id 
                    WHERE id = $id";
    
            if (!$this->db->query($sql)) {
                throw new Exception("Update failed: " . $this->db->error);
            }
    
            return true;
        } catch (Exception $e) {
            throw new Exception("Error updating contact: " . $e->getMessage());
        }
    }
    
    public function delete($id) {
        try {
            $id = $this->db->escape($id);
            $result = $this->db->query("DELETE FROM contacts WHERE id = $id");
            if (!$result) {
                throw new Exception("Delete operation failed: " . $this->db->error);
            }
            return true;
        } catch (Exception $e) {
            throw new Exception("Error deleting contact: " . $e->getMessage());
        }
    }
}