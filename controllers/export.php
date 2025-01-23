<?php
require_once '../config/config.php';
require_once '../models/Database.php';
require_once '../models/Contact.php';

$db = new Database();
$contact = new Contact($db);

$contacts = $contact->getAll();

if ($_GET['format'] === 'xml') {
    header('Content-Type: application/xml; charset=utf-8');
    header('Content-Disposition: attachment; filename=address_book.xml');

    $xml = new SimpleXMLElement('<address-book/>');

    foreach ($contacts as $c) {
        $entry = $xml->addChild('contact');
        $entry->addChild('name', htmlspecialchars($c['name']));
        $entry->addChild('first_name', htmlspecialchars($c['first_name']));
        $entry->addChild('email', htmlspecialchars($c['email']));
        $entry->addChild('street', htmlspecialchars($c['street']));
        $entry->addChild('zip_code', htmlspecialchars($c['zip_code']));
        $entry->addChild('city', htmlspecialchars($c['city_name']));
    }

    echo $xml->asXML();
} elseif ($_GET['format'] === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename=address_book.json');

    echo json_encode($contacts);
}