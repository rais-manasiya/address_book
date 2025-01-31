<?php
class City {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM cities");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching cities: " . $e->getMessage());
        }
    }
}
