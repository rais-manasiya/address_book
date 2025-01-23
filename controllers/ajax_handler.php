<?php
require_once '../config/config.php';
require_once '../models/Database.php';
require_once '../models/Contact.php';

$db = new Database();
$contact = new Contact($db);

function jsonResponse($status, $data = null) {
    echo json_encode(array_merge(['status' => $status], $data ? $data : []));
}
function respond($status, $message = null, $data = null) {
    echo json_encode(array_filter([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]));
}
function handlePost($contact) {
    $action = $_POST['action'] ?? null;
    switch ($action) {
        case 'add':
            $result = $contact->create($_POST);
            jsonResponse($result ? 'success' : 'error', $result ? [] : ['message' => 'Failed to add contact']);
            break;
        case 'update':
            $result = $contact->update($_POST['id'], $_POST);
            jsonResponse($result ? 'success' : 'error', $result ? [] : ['message' => 'Failed to update contact']);
            break;
        case 'delete':
            $result = $contact->delete($_POST['id']);
            jsonResponse($result ? 'success' : 'error', $result ? [] : ['message' => 'Failed to delete contact']);
            break;
        default:
            jsonResponse('error', ['message' => 'Invalid action']);
    }
}

function handleGet($contact) {
    $action = $_GET['action'] ?? null;
    switch ($action) {
        case 'get':
            $result = $contact->getById($_GET['id']);
            jsonResponse($result ? 'success' : 'error', $result ? ['contact' => $result] : ['message' => 'Contact not found']);
            break;
        default:
            jsonResponse('error', ['message' => 'Invalid action']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePost($contact);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handleGet($contact);
} else {
    jsonResponse('error', ['message' => 'Invalid request method']);
}
