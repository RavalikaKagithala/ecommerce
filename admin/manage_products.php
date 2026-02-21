<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Fetch all products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        td img {
            width: 80px;
            height: auto;
            border-radius: 4px;
        }
        .actions a {
            margin: 0 5px;
            padding: 5px 10px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .actions a:hover {
            background-color: #007bff;
            color: white;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
        }
        .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Products</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?= $product['id']; ?></td>
                <td><?= htmlspecialchars($product['name']); ?></td>
                <td>$<?= number_format($product['price'], 2); ?></td>
                <td><?= htmlspecialchars($product['description']); ?></td>
                <td>
                    <?php if(!empty($product['image'])): ?>
                        <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="edit_product.php?id=<?= $product['id']; ?>">Edit</a>
                    <a href="delete_product.php?id=<?= $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php" class="btn-back">⬅ Back to Dashboard</a>
</div>

</body>
</html>