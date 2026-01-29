<?php
include 'config.php';
date_default_timezone_set('Asia/Kolkata'); // Set your server's timezone here
$conn->query("SET time_zone = '+05:30'"); // For IST
$token = $_GET['token'] ?? '';
$error = '';
$success = '';

if (!$token) {
    $error = "Invalid reset link.";
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if (!$user) {
        $error = "Reset link is invalid.";
    } elseif (strtotime($user['token_expiry']) < time()) {
        $error = "Reset link is expired.";
    } elseif (isset($_POST['reset'])) {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $error = "Passwords do not match.";
        } elseif (strlen($_POST['password']) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $new_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL, token_expiry=NULL WHERE id=?");
            $stmt->bind_param("si", $new_pass, $user['id']);
            $stmt->execute();
            $success = "Password reset successfully. <a href='login.php'>Go to Login</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            background: #eef2f7;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .message {
            margin-top: 15px;
            font-size: 14px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        a {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Reset Password</h2>
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php elseif ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif ($user): ?>
            <form method="post">
                <input type="password" name="password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button name="reset">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
