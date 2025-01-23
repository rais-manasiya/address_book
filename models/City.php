<?php
class City {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        try {
            $result = $this->db->query("SELECT * FROM cities");
            if (!$result) {
                throw new Exception("Query failed: " . $this->db->error);
            }
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            throw new Exception("An error occurred while fetching cities: " . $e->getMessage());
        }
    }
}
