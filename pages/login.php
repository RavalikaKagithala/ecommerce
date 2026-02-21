<?php
include('../includes/db.php');
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        .error-message { color: #e74c3c; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<?php include('../includes/welcome_banner.php'); ?>

<div class="login-container">
    <h2>Login</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
</div>

</body>
</html>