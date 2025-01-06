<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$query = $_GET['query'];
$sql = "SELECT * FROM products WHERE product_name LIKE ?";
$stmt = $conn->prepare($sql);
$search = "%" . $query . "%";
$stmt->bind_param('s', $search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Search Results</title>
</head>
<body>
<div class="container mt-5">
    <h3>Search Results</h3>
    <a href="index.php" class="btn btn-secondary mb-3">Back</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Product Name</th>
            <th>SKU</th>
            <th>Quantity</th>
            <th>Rack Zone</th>
            <th>Rack Number</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['product_name'] ?></td>
                <td><?= $row['sku'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['rack_zone'] ?></td>
                <td><?= $row['rack_number'] ?></td>
                <td><img src="uploads/<?= $row['image'] ?>" width="50"></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
