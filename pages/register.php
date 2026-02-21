<?php
include('../includes/db.php');  // Database connection
session_start();

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = 'user'; // Default role for users

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $error_message = "Email is already registered!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$email, $password, $role]);

        // Log the user in after successful registration
        $_SESSION['user_id'] = $conn->lastInsertId();
        header("Location: ../index.php"); // Redirect to the homepage
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4, #fad0c4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: #fff;
            padding: 35px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            color: #ff6a00;
            margin-bottom: 25px;
            text-shadow: 1px 1px 2px #f0f0f0;
        }
        label {
            display: block;
            font-size: 1.1em;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
        }
        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(to right, #ff6a00, #ee0979);
            color: white;
            font-size: 1.1em;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(to right, #ee0979, #ff6a00);
        }
        .error-message {
            color: #e74c3c;
            font-size: 1em;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Register</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
</div>

</body>
</html>