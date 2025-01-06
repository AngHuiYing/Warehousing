<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$id = $_GET['id'];
$query = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: index.php');
    exit;
} else {
    echo "Failed to delete product.";
}
?>
