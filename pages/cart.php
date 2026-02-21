<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

$user_id = $_SESSION['user_id'];

/* =========================
   HANDLE UPDATE QUANTITY
========================= */
if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $user_id, $product_id]);
    }
}

/* =========================
   HANDLE REMOVE ITEM
========================= */
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

/* =========================
   FETCH CART ITEMS
========================= */
$stmt = $conn->prepare("SELECT cart.product_id, cart.id AS cart_id, products.name, products.price, cart.quantity 
                        FROM cart 
                        JOIN products ON cart.product_id = products.id 
                        WHERE cart.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_cost = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
        }

        .cart-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #333;
            color: white;
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
        }

        button {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .update-btn {
            background: #28a745;
            color: white;
        }

        .remove-btn {
            background: #dc3545;
            color: white;
        }

        .total {
            text-align: right;
            font-size: 18px;
            margin-top: 20px;
            font-weight: bold;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Your Shopping Cart</h2>

    <?php if (count($cart_items) > 0): ?>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>

            <?php foreach ($cart_items as $item): ?>
                <?php 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_cost += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td>$<?= number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity']; ?>" min="1">
                            <button type="submit" name="update_quantity" class="update-btn">Update</button>
                        </form>
                    </td>
                    <td>$<?= number_format($subtotal, 2); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $item['product_id']; ?>">
                            <button type="submit" name="remove_from_cart" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total">
            Total: $<?= number_format($total_cost, 2); ?>
        </div>

    <?php else: ?>
        <div class="empty-cart">
            Your cart is empty.
        </div>
    <?php endif; ?>

</div>

</body>
</html>