<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .success-message {
            text-align: center;
            margin-top: 15px;
            color: green;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>User Registration</h2>
        <form method="post">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="register">Register</button>
        </form>

        <?php
        if (isset($_POST['register'])) {
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $_POST['name'], $_POST['email'], $_POST['phone'], password_hash($_POST['password'], PASSWORD_DEFAULT));
            $stmt->execute();
            echo "<div class='success-message'>✅ Registered successfully. <a href='login.php'>Login</a></div>";
        }
        ?>
    </div>
</body>
</html>
