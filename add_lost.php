<?php
include 'config.php';
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Lost Item</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .form-container input,
        .form-container select,
        .form-container textarea,
        .form-container button {
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-container select {
            background-color: #fff;
        }

        .form-container textarea {
            resize: vertical;
        }

        .form-container button {
            background-color: rgb(3, 3, 3);
            color: #fff;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: rgb(28, 174, 36);
        }

        .image-preview {
            display: block;
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .success-modal {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            margin-top: 15px;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Report Lost Item</h2>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="description" placeholder="Description" required></textarea>

            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Wallet">Wallet</option>
                <option value="Phone">Phone</option>
                <option value="ID Card">ID Card</option>
                <option value="Keys">Keys</option>
                <option value="Bag">Bag</option>
                <option value="Others">Others</option>
            </select>

            <input type="text" name="location" placeholder="Location" required>
            <input type="date" name="date" id="date" required max="<?= date('Y-m-d') ?>">
            <input type="file" name="image" id="imageInput" accept="image/*" required onchange="previewImage()">
            <img id="preview" class="image-preview" style="display:none;">
            <button name="submit">Submit</button>
        </form>

        <?php
        if (isset($_POST['submit'])) {
            $img = "uploads/" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $img);

            $stmt = $conn->prepare("INSERT INTO lost_items (title, description, category, location, date_lost, image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssi", 
                $_POST['title'],
                $_POST['description'],
                $_POST['category'],
                $_POST['location'],
                $_POST['date'],
                $img,
                $_SESSION['user']['id']
            );

            $stmt->execute();
            echo "<div class='success-modal'>📢 Lost item submitted for approval!</div>";
        }
        ?>
    </div>

    <script>
        function previewImage() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('preview');
            const file = input.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        }

        function validateForm() {
            const fileInput = document.getElementById('imageInput');
            const file = fileInput.files[0];
            const dateInput = document.getElementById('date');
            const today = new Date().toISOString().split("T")[0];

            if (file && file.size > 5 * 1024 * 1024) {
                alert("Image size must be less than 5MB.");
                return false;
            }

            if (dateInput.value > today) {
                alert("Date cannot be in the future.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
