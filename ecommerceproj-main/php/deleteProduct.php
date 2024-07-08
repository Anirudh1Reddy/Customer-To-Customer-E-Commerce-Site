<?php
include 'dbh_inc.php';

$name = $_POST['nameP'];

file_put_contents('log.txt', "Received product name: $name\n", FILE_APPEND);

$sql = "DELETE FROM products WHERE name = '$name'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    $error = $conn->error;
    file_put_contents('log.txt', "Error: $error\n", FILE_APPEND);
    echo json_encode(['success' => false, 'error' => 'Failed to delete product']);
}

$conn->close();
?>*/
