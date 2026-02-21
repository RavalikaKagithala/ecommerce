<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Handle image upload
    $image = $product['image']; // keep existing image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($tmp_name, "../images/" . $image_name);
        $image = $image_name;
    }

    // Update product in database
    $update = $conn->prepare("UPDATE products SET name = :name, price = :price, description = :description, image = :image WHERE id = :id");
    $update->bindParam(':name', $name);
    $update->bindParam(':price', $price);
    $update->bindParam(':description', $description);
    $update->bindParam(':image', $image);
    $update->bindParam(':id', $product_id, PDO::PARAM_INT);

    if ($update->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        $error = "Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fa; }
        .container { width: 500px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);}
        h2 { text-align: center; }
        form { display: flex; flex-direction: column; }
        label { margin-top: 10px; }
        input[type="text"], input[type="number"], textarea { padding: 8px; margin-top: 5px; border-radius: 4px; border: 1px solid #ccc; }
        input[type="file"] { margin-top: 5px; }
        button { margin-top: 20px; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
        .btn-back { display: inline-block; margin-top: 15px; text-decoration: none; color: #007bff; }
        .btn-back:hover { text-decoration: underline; }
        .error { color: red; margin-top: 10px; }
        img { margin-top: 10px; width: 100px; height: auto; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($product['description']); ?></textarea>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image">
        <?php if(!empty($product['image'])): ?>
            <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
        <?php endif; ?>

        <button type="submit">Update Product</button>
    </form>

    <a href="manage_products.php" class="btn-back">⬅ Back to Manage Products</a>
</div>

</body>
</html>