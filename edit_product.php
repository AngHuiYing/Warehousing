<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include 'db.php';

$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $sku = $_POST['sku'];
    $quantity = $_POST['quantity'];
    $rack_zone = $_POST['rack_zone'];
    $rack_number = $_POST['rack_number'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $query = "UPDATE products SET product_name = ?, sku = ?, quantity = ?, rack_zone = ?, rack_number = ?, image = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssisssi', $product_name, $sku, $quantity, $rack_zone, $rack_number, $image, $id);
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $query = "UPDATE products SET product_name = ?, sku = ?, quantity = ?, rack_zone = ?, rack_number = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssissi', $product_name, $sku, $quantity, $rack_zone, $rack_number, $id);
    }

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Product</title>
</head>
<body>
<div class="container mt-5">
    <h3>Edit Product</h3>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="product_name" class="form-control" value="<?= $product['product_name'] ?>" required>
        </div>
        <div class="mb-3">
            <label>SKU</label>
            <input type="text" name="sku" class="form-control" value="<?= $product['sku'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Rack Zone</label>
            <input type="text" name="rack_zone" class="form-control" value="<?= $product['rack_zone'] ?>">
        </div>
        <div class="mb-3">
            <label>Rack Number</label>
            <input type="text" name="rack_number" class="form-control" value="<?= $product['rack_number'] ?>">
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
