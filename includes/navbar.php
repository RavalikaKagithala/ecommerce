<?php
// Navbar with Welcome Banner included
include 'welcome_banner.php';
?>

<style>
/* Navbar Styling */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #333;
    padding: 10px 20px;
    font-family: Arial, sans-serif;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 8px 15px;
    transition: background-color 0.3s, color 0.3s;
    border-radius: 5px;
}

.navbar a:hover {
    background-color: #4CAF50;
    color: white;
}

.navbar .logo {
    font-weight: bold;
    font-size: 20px;
    color: #4CAF50;
}

.navbar .links {
    display: flex;
    gap: 10px;
}
</style>

<div class="navbar">
    <div class="logo">MyShop</div>
    <div class="links">
        <a href="../index.php">Home</a>
        <a href="../pages/login.php">Login</a>
        <a href="../pages/register.php">Register</a>
        <a href="../pages/cart.php">Cart</a>
    </div>
</div>