<?php
include 'config.php';

// Handle approve/reject action if requested
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action !== 'approve' && $action !== 'reject') {
        die("Invalid action.");
    }

    $status = $action === 'approve' ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE claims SET claim_status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = $action === 'approve' ? "Claim approved successfully." : "Claim rejected successfully.";
        // Redirect to avoid resubmission on refresh
        header("Location: approve_claims.php?message=" . urlencode($message));
        exit();
    } else {
        die("Failed to update claim status.");
    }
}

// Fetch pending claims
$result = $conn->query("SELECT * FROM claims WHERE claim_status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Claims</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center">Pending Claims</h2>

    <!-- Success message alert -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-success text-center">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <?php
    if ($result->num_rows === 0) {
        echo "<div class='alert alert-info text-center'>No pending claims found.</div>";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card mb-3 shadow-sm'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>Item ID: {$row['item_id']}</h5>";
            echo "<p><strong>User Email:</strong> {$row['user_email']}</p>";
            echo "<p><strong>Message:</strong> {$row['claim_message']}</p>";
            echo "<a href='approve_claims.php?id={$row['id']}&action=approve' class='btn btn-success btn-sm me-2'>Approve</a>";
            echo "<a href='approve_claims.php?id={$row['id']}&action=reject' class='btn btn-danger btn-sm'>Reject</a>";
            echo "</div></div>";
        }
    }
    ?>
</div>

</body>
</html>
