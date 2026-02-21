<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$message = "";

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];

    move_uploaded_file($_FILES['image']['tmp_name'], "../images/$image");

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $image]);

    $message = "✅ Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Product</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    margin: 0;
}

.container {
    width: 450px;
    margin: 60px auto;
    background: #ffffff;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: fadeIn 0.5s ease-in-out;
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

label {
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: #555;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 18px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: 0.3s;
}

input:focus, textarea:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 5px rgba(102,126,234,0.5);
}

textarea {
    resize: none;
    height: 90px;
}

button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #28a745, #218838);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: linear-gradient(135deg, #218838, #1e7e34);
    transform: translateY(-2px);
}

.success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: bold;
}

.back-link {
    text-align: center;
    margin-top: 20px;
}

.back-link a {
    text-decoration: none;
    color: #667eea;
    font-weight: 600;
}

.back-link a:hover {
    text-decoration: underline;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body>

<div class="container">
    <h2>➕ Add New Product</h2>

    <?php if ($message): ?>
        <div class="success"><?= $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name</label>
        <input type="text" name="name" required>

        <label>Price</label>
        <input type="number" step="0.01" name="price" required>

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Product Image</label>
        <input type="file" name="image" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <div class="back-link">
        <a href="manage_products.php">⬅ Back to Manage Products</a>
    </div>
</div>

</body>
</html>