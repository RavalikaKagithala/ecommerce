<?php
session_start();
include '../includes/db.php';

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        header("Location: dashboard.php");
        exit();

    } else {
        $error = "Invalid credentials or not an admin.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            padding: 40px;
            width: 350px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            color: white;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 1px;
        }

        .login-box label {
            font-weight: 500;
            font-size: 14px;
        }

        .login-box input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 20px;
            border: none;
            border-radius: 6px;
            outline: none;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #ff9800;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-box button:hover {
            background: #e68900;
            transform: scale(1.05);
        }

        .error {
            background: rgba(255, 0, 0, 0.2);
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .footer-text {
            text-align: center;
            margin-top: 15px;
            font-size: 13px;
            opacity: 0.8;
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Admin Panel Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>

    <div class="footer-text">
        Secure Admin Access
    </div>
</div>

</body>
</html>