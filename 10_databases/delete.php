<?php

$pdo = require 'db.php';

if (isset($_GET['id'])) {
    $contactId = $_GET['id'];

    // Fetch the contact's image name before deleting
    $stmt = $pdo->prepare("SELECT image FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $contactId]);
    $contact = $stmt->fetch(PDO::FETCH_ASSOC);

    // If there is an image, delete it from the server
    if ($contact && $contact['image']) {
        $imagePath = 'uploads/' . $contact['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);  // Delete the image file from the server
        }
    }

    // Now delete the contact from the database
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = :id");
    $stmt->execute([':id' => $contactId]);
    echo "Contact Deleted";
}