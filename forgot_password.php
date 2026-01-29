<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'config.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));
    $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        // Update token and expiry
        $stmt = $conn->prepare("UPDATE users SET reset_token=?, token_expiry=? WHERE email=?");
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        // Make sure to match your actual project folder name
        $resetLink = "http://localhost/lost_found_system/reset_password.php?token=$token";

        // Send email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'naveedshaik1503@gmail.com';  // Your Gmail
            $mail->Password = 'zbtw ouvn owvz erbf';         // Your Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('naveedshaik1503@gmail.com', 'Support Team');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Link';
            $mail->Body = "
                <p>Hi,</p>
                <p>Click the link below to reset your password. This link is valid for 1 hour.</p>
                <p><a href='$resetLink'>$resetLink</a></p>
            ";

            $mail->send();
            $message = "<span style='color:green;'>Reset link sent to your email.</span>";
        } catch (Exception $e) {
            $message = "<span style='color:red;'>Mailer Error: {$mail->ErrorInfo}</span>";
        }
    } else {
        $message = "<span style='color:red;'>Email not found in our system.</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            background: #f7f9fc;
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
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            margin-top: 15px;
            font-size: 14px;
        }
        a {
            word-break: break-word;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Forgot Password</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button name="submit">Send Reset Link</button>
        </form>
        <?php if ($message) echo "<div class='message'>$message</div>"; ?>
    </div>
</body>
</html>
