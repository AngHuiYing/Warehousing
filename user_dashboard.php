<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

include 'db.php';

$search_query = "";
if (isset($_GET['query'])) {
    $search_query = $_GET['query'];
    $query = "SELECT * FROM products WHERE product_name LIKE ?";
    $stmt = $conn->prepare($query);
    $search_term = "%$search_query%";
    $stmt->bind_param('s', $search_term);
} else {
    $query = "SELECT * FROM products";
    $stmt = $conn->prepare($query);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>User Dashboard</title>
</head>
<body>
<div class="container mt-5">
    <h3>Welcome, <?= $_SESSION['username']; ?>!</h3>
    <a href="user_logout.php" class="btn btn-danger mb-3">Logout</a>
    <form class="d-flex mb-3" method="GET">
        <input class="form-control me-2" type="search" name="query" value="<?= $search_query ?>" placeholder="Search products..." aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Rack Zone</th>
                <th>Rack Number</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['product_name'] ?></td>
                <td><?= $row['sku'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['rack_zone'] ?></td>
                <td><?= $row['rack_number'] ?></td>
                <td><img src="uploads/<?= $row['image'] ?>" width="50" alt="Product Image"></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
