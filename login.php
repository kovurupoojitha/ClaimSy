<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            background-image: url('https://wallpapercave.com/wp/wp9078348.png');
            
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            width: 320px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container p {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }
        .login-container a {
            color: #007bff;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="login">Login</button>
            <p><a href="forgot_password.php">Forgot your password?</a></p>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </form>
        <?php
        if (isset($_POST['login'])) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            if ($result && password_verify($_POST['password'], $result['password'])) {
    session_start();
    $_SESSION['user'] = $result;
    $_SESSION['user_role'] = $result['role']; // Add this line to store role

    // Redirect based on role
    if ($result['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
} else {
                echo '<div class="error">Invalid credentials</div>';
            }
        }
        ?>
    </div>
</body>
</html>
