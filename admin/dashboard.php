<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f1f4f9;
}

/* Top Header */
.header {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: white;
    padding: 20px;
    text-align: center;
    font-size: 22px;
    letter-spacing: 1px;
}

/* Dashboard Container */
.dashboard {
    width: 80%;
    margin: 40px auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 25px;
}

/* Cards */
.card {
    background: white;
    padding: 30px;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 8px 18px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}

/* Card Links */
.card a {
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

/* Different Colors */
.add { border-top: 6px solid #28a745; }
.manage { border-top: 6px solid #007bff; }
.logout { border-top: 6px solid #dc3545; }

.footer {
    text-align: center;
    margin-top: 50px;
    padding: 15px;
    font-size: 14px;
    color: #777;
}
</style>

</head>
<body>

<div class="header">
    Welcome to Admin Dashboard
</div>

<div class="dashboard">

    <div class="card add">
        <a href="add_product.php">➕ Add Product</a>
    </div>

    <div class="card manage">
        <a href="manage_products.php">📦 Manage Products</a>
    </div>

    <div class="card logout">
        <a href="logout.php">🚪 Logout</a>
    </div>

</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Ecommerce Admin Panel
</div>

</body>
</html>