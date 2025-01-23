<?php
require_once './config/config.php';
require_once './models/Database.php';
require_once './models/Contact.php';

class ContactTest {
    private $db;
    private $contact;

    public function __construct() {
        $this->db = new Database();
        $this->contact = new Contact($this->db);
    }

    public function testCreate() {
        $data = [
            'name' => 'Test User',
            'first_name' => 'Test',
            'email' => 'test@example.com',
            'street' => 'Test Street',
            'zip_code' => '12345',
            'city_id' => 1
        ];

        $result = $this->contact->create($data);
        assert($result === true, 'Create contact failed');

        $lastId = $this->db->getLastInsertId();
        $createdContact = $this->contact->getById($lastId);
        assert($createdContact['name'] === $data['name'], 'Created contact name does not match');
    }

    public function testUpdate() {
        $data = [
            'name' => 'Updated User',
            'first_name' => 'Updated',
            'email' => 'updated@example.com',
            'street' => 'Updated Street',
            'zip_code' => '54321',
            'city_id' => 2
        ];

        $lastId = $this->db->getLastInsertId();
        $result = $this->contact->update($lastId, $data);
        assert($result === true, 'Update contact failed');

        $updatedContact = $this->contact->getById($lastId);
        assert($updatedContact['name'] === $data['name'], 'Updated contact name does not match');
    }

    public function testDelete() {
        $lastId = $this->db->getLastInsertId();
        $result = $this->contact->delete($lastId);
        assert($result === true, 'Delete contact failed');

        $deletedContact = $this->contact->getById($lastId);
        assert($deletedContact === null, 'Contact was not deleted');
    }

    public function runTests() {
        $this->testCreate();
        $this->testUpdate();
        $this->testDelete();
        echo "All tests passed successfully!";
    }
}

$test = new ContactTest();
$test->runTests();