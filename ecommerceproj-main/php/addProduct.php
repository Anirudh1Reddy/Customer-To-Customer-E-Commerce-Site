<?php
include 'dbh_inc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!isset($_POST['nameP'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing product name parameter']);
    exit;
}

$name = $_POST['nameP'];
$description = $_POST['descriptionP'];
$imageUrl = $_POST['image_url'];
$price = $_POST['priceP'];
$deliveryOpt = $_POST['delivery_opt'];
$category = $_POST['category_tag'];

$sql = "INSERT INTO products (name, description, picture_url, rating, price, delivery_methods, categories) VALUES (?, ?, ?, '0', ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $description, $imageUrl, $price, $deliveryOpt, $category);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'New record created successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to insert new record']);
}

$stmt->close();
$conn->close();
?>