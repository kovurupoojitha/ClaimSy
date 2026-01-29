<?php
include 'config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die('Not authorized');
}

$limit = 5; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$message = "";

// Handle Approve or Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $table = $type === 'lost' ? 'lost_items' : 'found_items';

    if (isset($_POST['approve'])) {
        $stmt = $conn->prepare("UPDATE $table SET status='approved' WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $message = "✅ Item approved.";
    } elseif (isset($_POST['reject'])) {
        $stmt = $conn->prepare("UPDATE $table SET status='rejected' WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $message = "❌ Item rejected.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Approvals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            padding: 40px;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }
        .approval-form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .approval-form button {
            margin-left: 10px;
        }
        .message {
            text-align: center;
            font-weight: 500;
            margin-bottom: 20px;
            color: green;
        }
        .rejected {
            color: red;
        }
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }
        .history-section {
            margin-top: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4 text-center">Admin Pending Approvals</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <?php
    $pending = $conn->query("(
        SELECT 'lost' AS type, id, title FROM lost_items WHERE status='pending'
        UNION
        SELECT 'found' AS type, id, title FROM found_items WHERE status='pending'
        ) LIMIT $limit OFFSET $offset");

    $total_result = $conn->query("(
        SELECT id FROM lost_items WHERE status='pending'
        UNION
        SELECT id FROM found_items WHERE status='pending')");

    $total_items = $total_result->num_rows;
    $total_pages = ceil($total_items / $limit);

    if ($pending->num_rows === 0) {
        echo "<p class='text-center text-muted'>No pending items.</p>";
    }

    while ($row = $pending->fetch_assoc()) {
        echo "
        <form method='post' class='approval-form'>
            <input type='hidden' name='type' value='{$row['type']}'>
            <input type='hidden' name='id' value='{$row['id']}'>
            <span><strong>" . ucfirst($row['type']) . ":</strong> {$row['title']}</span>
            <div>
                <button name='approve' class='btn btn-success btn-sm'>Approve</button>
                <button name='reject' class='btn btn-danger btn-sm'>Reject</button>
            </div>
        </form>";
    }

    if ($total_pages > 1) {
        echo '<nav><ul class="pagination">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = $i === $page ? 'active' : '';
            echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
        }
        echo '</ul></nav>';
    }
    ?>

    <div class="history-section">
        <h4 class="text-center mt-5">Approval History</h4>
        <?php
        $history = $conn->query("(
            SELECT 'lost' AS type, title, status FROM lost_items WHERE status IN ('approved', 'rejected')
            UNION
            SELECT 'found' AS type, title, status FROM found_items WHERE status IN ('approved', 'rejected')
            ) ORDER BY status DESC LIMIT 10");

        if ($history->num_rows === 0) {
            echo "<p class='text-center text-muted'>No approval history.</p>";
        } else {
            while ($item = $history->fetch_assoc()) {
                $statusColor = $item['status'] === 'approved' ? 'text-success' : 'text-danger';
                echo "<p><strong>" . ucfirst($item['type']) . ":</strong> {$item['title']} <span class='$statusColor'>({$item['status']})</span></p>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
