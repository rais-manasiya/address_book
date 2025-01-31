<?php
require_once '../config/config.php';
require_once '../models/Database.php';
require_once '../models/Contact.php';
require_once '../models/Group.php';

$db = new Database();
$contact = new Contact($db);
$group = new Group($db);

function jsonResponse($status, $data = null) {
    echo json_encode(array_merge(['status' => $status], $data ? $data : []));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    switch ($action) {
        case 'add_contact':
            try {
                $result = $contact->create($_POST);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'update_contact':
            try {
                $result = $contact->update($_POST['id'], $_POST);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'delete_contact':
            try {
                $result = $contact->delete($_POST['id']);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'add_group':
            try {
                $result = $group->create($_POST['name']);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'update_group':
            try {
                $result = $group->update($_POST['id'], $_POST['name']);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'delete_group':
            try {
                $result = $group->delete($_POST['id']);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'add_contact_to_group':
            try {
                $result = $group->addContact($_POST['group_id'], $_POST['contact_id']);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'remove_contact_from_group':
            try {
                $result = $group->removeContact($_POST['group_id'], $_POST['contact_id']);
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'connect_groups':
            try {
                $result = $group->connectGroups($_POST['parent_group_id'], $_POST['child_group_id']);
                if ($result) {
                    $group->updateInheritedContacts($_POST['child_group_id']);
                }
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'disconnect_groups':
            try {
                $result = $group->disconnectGroups($_POST['parent_group_id'], $_POST['child_group_id']);
                if ($result) {
                    $group->updateInheritedContacts($_POST['child_group_id']);
                }
                jsonResponse('success');
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        default:
            jsonResponse('error', ['message' => 'Invalid action']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? null;
    switch ($action) {
        case 'get_contact':
            try {
                $result = $contact->getById($_GET['id']);
                jsonResponse('success', ['contact' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_all_contacts':
            try {
                $result = $contact->getAll();
                jsonResponse('success', ['contacts' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_contacts_by_tag':
            try {
                $result = $contact->getByTag($_GET['tag']);
                jsonResponse('success', ['contacts' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_all_tags':
            try {
                $result = $contact->getAllTags();
                jsonResponse('success', ['tags' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_group':
            try {
                $result = $group->getById($_GET['id']);
                jsonResponse('success', ['group' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_all_groups':
            try {
                $result = $group->getAll();
                jsonResponse('success', ['groups' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_group_contacts':
            try {
                $result = $group->getContacts($_GET['id']);
                jsonResponse('success', ['contacts' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        case 'get_connected_groups':
            try {
                $result = $group->getConnectedGroups($_GET['id']);
                jsonResponse('success', ['groups' => $result]);
            } catch (Exception $e) {
                jsonResponse('error', ['message' => $e->getMessage()]);
            }
            break;
        default:
            jsonResponse('error', ['message' => 'Invalid action']);
    }
} else {
    jsonResponse('error', ['message' => 'Invalid request method']);
}