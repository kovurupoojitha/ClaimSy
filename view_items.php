<?php include 'config.php'; ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container mt-4">
    <h2>Search Lost & Found Items</h2>
    <form method="GET" class="mb-4">
        <input type="text" name="q" placeholder="Search by title or location" class="form-control" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
        <button class="btn btn-primary mt-2">Search</button>
    </form>

    <?php
    $search = isset($_GET['q']) ? "%" . $_GET['q'] . "%" : "%";

    // Lost Items
    echo "<h4 class='mb-3 text-danger'>🔍 Lost Items</h4>";
    $stmt = $conn->prepare("SELECT * FROM lost_items WHERE status='approved' AND (title LIKE ? OR location LIKE ?) ORDER BY date_lost DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $lost = $stmt->get_result();
    if ($lost->num_rows > 0) {
        while ($row = $lost->fetch_assoc()) {
            echo "
                <div class='card mb-3'>
                    <div class='card-body'>
                        <h5 class='card-title text-danger'>{$row['title']}</h5>
                        <p class='card-text'>{$row['description']}</p>
                        <p><strong>Location:</strong> {$row['location']}</p>
                        <p><strong>Date Lost:</strong> {$row['date_lost']}</p>
                        <img src='{$row['image']}' width='150' class='rounded border'>
                    </div>
                </div>
            ";
        }
    } else {
        echo "<p class='text-muted'>No lost items found.</p>";
    }

    // Found Items
    echo "<h4 class='mb-3 mt-5 text-success'>✅ Found Items</h4>";
    $stmt = $conn->prepare("SELECT * FROM found_items WHERE status='approved' AND (title LIKE ? OR location LIKE ?) ORDER BY date_found DESC");
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $found = $stmt->get_result();
    if ($found->num_rows > 0) {
        while ($row = $found->fetch_assoc()) {
            echo "
                <div class='card mb-3'>
                    <div class='card-body'>
                        <h5 class='card-title text-success'>{$row['title']}</h5>
                        <p class='card-text'>{$row['description']}</p>
                        <p><strong>Location:</strong> {$row['location']}</p>
                        <p><strong>Date Found:</strong> {$row['date_found']}</p>
                        <img src='{$row['image']}' width='150' class='rounded border mb-2'><br>
                        <a href='claim_item.php?item_id={$row['id']}' class='btn btn-warning btn-sm'>Claim</a>
                    </div>
                </div>
            ";
        }
    } else {
        echo "<p class='text-muted'>No found items found.</p>";
    }
    ?>
</div>
