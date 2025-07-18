<?php

$uploadsDir = 'uploads/';
$pdo = require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $phone = filter_input(INPUT_POST, "phone", FILTER_SANITIZE_NUMBER_INT);

    if ($name && $email && $phone && isset($_FILES['image'])) {

        // Ensure the uploads directory exists
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $imagePath = $uploadsDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone, image) VALUES (:name, :email, :phone, :image)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':image' => $imagePath  // Save the image file name to the database
            ]);

            echo "Contact added: $name ($email, $phone)";
        } else {
            echo "Image upload failed";
        }

    } else {
        echo "Invalid input!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<form action="" method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name">

    <label>Email:</label>
    <input type="email" name="email">

    <label>Phone:</label>
    <input type="text" name="phone">

    <label>Contact Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit">Add Contact</button>
</form>
</body>
</html>