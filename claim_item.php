<?php
include 'config.php';

if (!isset($_GET['item_id'])) die("Invalid item.");

$item_id = $_GET['item_id'];
$message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $claim_msg = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO claims (user_email, item_id, claim_message) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $email, $item_id, $claim_msg);
    $stmt->execute();

    $message = "✅ Your claim has been submitted!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Claim Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card mx-auto shadow" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center">Claim Item #<?= htmlspecialchars($item_id) ?></h3>

            <?php if ($message): ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" name="email" id="email" class="form-control" required placeholder="Enter your email...">
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Proof for Claim</label>
                    <textarea name="message" id="message" class="form-control" rows="4" required placeholder="Explain why this item belongs to you..."></textarea>
                </div>

                <button name="submit" class="btn btn-primary w-100">Submit Claim</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
