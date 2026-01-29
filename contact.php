<?php
include 'config.php'; // Import database connection

$name = $email = $subject = $message = "";
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = htmlspecialchars(trim($_POST["name"]));
    $email    = htmlspecialchars(trim($_POST["email"]));
    $subject  = htmlspecialchars(trim($_POST["subject"]));
    $message  = htmlspecialchars(trim($_POST["message"]));

    // Validate inputs
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $subject, $message);

            if ($stmt->execute()) {
                $success = "Message submitted successfully!";
                $name = $email = $subject = $message = "";
            } else {
                $error = "Error submitting message.";
            }

            $stmt->close();
        } else {
            $error = "Invalid email format.";
        }
    } else {
        $error = "All fields are required.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - Lost and Found</title>
    <style>
        body {
            font-family: Arial;
            margin: 20px;
        }
        form {
            max-width: 500px;
            margin: auto;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h2>Contact Us</h2>

<?php if ($success): ?>
    <p class="success"><?= $success ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="post" action="contact.php">
    <label for="name">Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

    <label for="subject">Subject:</label>
    <input type="text" name="subject" value="<?= htmlspecialchars($subject) ?>" required>

    <label for="message">Message:</label>
    <textarea name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>

    <button type="submit">Send Message</button>
</form>

</body>
</html>
