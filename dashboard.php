<?php
include 'config.php';
if (!isset($_SESSION['user'])) header('Location: login.php');

// Set correct timezone
date_default_timezone_set('Asia/Kolkata');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
        }

        .dashboard {
            max-width: 600px;
            margin: 80px auto;
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        .dashboard h2 {
            margin-bottom: 10px;
            color: #2c3e50;
            font-size: 26px;
        }

        .dashboard .datetime {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .dashboard a {
            display: inline-block;
            padding: 12px 22px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            transition: 0.3s;
        }

        .dashboard a:hover {
            background-color: #0056b3;
        }

        .dashboard a i {
            margin-right: 8px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            .dashboard {
                margin: 40px 20px;
                padding: 30px 20px;
            }

            .btn-group {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
        <div class="datetime"><?= date("l, F j Y - g:i A") ?></div>

        <div class="btn-group">
            <a href="add_lost.php"><i class="fas fa-search"></i> Report Lost Item</a>
            <a href="add_found.php"><i class="fas fa-box"></i> Report Found Item</a>
            <a href="view_items.php"><i class="fas fa-list"></i> View All Items</a>
        </div>

        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <div class="mt-4">
            <p>Need help? <a href="contact.php">Contact Us</a></p>
            <p>© 2025 Lost & Found. All rights reserved.</p>
            <p>Version 1.0.0</p>       
    </div>
</body>
</html>
