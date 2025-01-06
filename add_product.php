<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $sku = $_POST['sku'];
    $quantity = $_POST['quantity'];
    $rack_zone = $_POST['rack_zone'];
    $rack_number = $_POST['rack_number'];

    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $query = "INSERT INTO products (product_name, sku, quantity, rack_zone, rack_number, image) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssisss', $product_name, $sku, $quantity, $rack_zone, $rack_number, $image);

        if ($stmt->execute()) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Failed to add product.";
        }
    } else {
        $error = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Add Product</title>
</head>
<body>
<div class="container mt-5">
    <h3>Add Product</h3>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Rack Zone</label>
            <input type="text" name="rack_zone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rack Number</label>
            <input type="text" name="rack_number" class="form-control">
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
