<?php
session_start();
include '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $exists = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($exists) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }
    $success_message = "Product added to cart!";
}

// Fetch all products
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .product-card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 200px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .product-card h3 {
            margin: 10px 0 5px;
            font-size: 18px;
            color: #ff6a00;
        }
        .product-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .product-card button {
            margin-top: 10px;
            padding: 10px;
            width: 100%;
            background: linear-gradient(to right, #ff6a00, #ee0979);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        .product-card button:hover {
            background: linear-gradient(to right, #ee0979, #ff6a00);
        }
        .success-message {
            text-align: center;
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>Our Products</h2>

<?php if (isset($success_message)) : ?>
    <p class="success-message"><?= htmlspecialchars($success_message); ?></p>
<?php endif; ?>

<div class="products-container">
    <?php foreach ($products as $product) : ?>
        <div class="product-card">
            <img src="../images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>">
            <h3><?= htmlspecialchars($product['name']); ?></h3>
            <p>₹<?= number_format($product['price'], 2); ?></p>
            <p><?= htmlspecialchars($product['description']); ?></p>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>