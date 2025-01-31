<?php
require_once '../config/config.php';
require_once '../models/Database.php';
require_once '../models/Contact.php';
require_once '../models/Group.php';

$db = new Database();
$contact = new Contact($db);
$group = new Group($db);

$contacts = $contact->getAll();
$groups = $group->getAll();

if ($_GET['format'] === 'xml') {
    header('Content-Type: application/xml; charset=utf-8');
    header('Content-Disposition: attachment; filename=address_book.xml');

    $xml = new SimpleXMLElement('<address-book/>');

    $contactsElement = $xml->addChild('contacts');
    foreach ($contacts as $c) {
        $entry = $contactsElement->addChild('contact');
        $entry->addChild('name', htmlspecialchars($c['name']));
        $entry->addChild('first_name', htmlspecialchars($c['first_name']));
        $entry->addChild('email', htmlspecialchars($c['email']));
        $entry->addChild('street', htmlspecialchars($c['street']));
        $entry->addChild('zip_code', htmlspecialchars($c['zip_code']));
        $entry->addChild('city', htmlspecialchars($c['city_name']));
        $entry->addChild('tags', htmlspecialchars($c['tags']));
    }

    $groupsElement = $xml->addChild('groups');
    foreach ($groups as $g) {
        $groupEntry = $groupsElement->addChild('group');
        $groupEntry->addChild('name', htmlspecialchars($g['name']));
        $groupContacts = $group->getContacts($g['id']);
        $groupContactsElement = $groupEntry->addChild('contacts');
        foreach ($groupContacts as $gc) {
            $contactEntry = $groupContactsElement->addChild('contact');
            $contactEntry->addChild('name', htmlspecialchars($gc['name']));
            $contactEntry->addChild('first_name', htmlspecialchars($gc['first_name']));
            $contactEntry->addChild('email', htmlspecialchars($gc['email']));
            $contactEntry->addChild('is_inherited', $gc['is_inherited']);
        }
    }

    echo $xml->asXML();
} elseif ($_GET['format'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=address_book.json');

    $data = [
        'contacts' => $contacts,
        'groups' => []
    ];

    foreach ($groups as $g) {
        $groupData = $g;
        $groupData['contacts'] = $group->getContacts($g['id']);
        $data['groups'][] = $groupData;
    }

    echo json_encode($data);
}