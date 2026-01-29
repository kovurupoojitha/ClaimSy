<?php
include 'config.php';
$email = $_GET['email']; // or use session for logged in user

$stmt = $conn->prepare("SELECT * FROM claims WHERE user_email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "Item ID: {$row['item_id']} - Status: {$row['claim_status']}<br>";
}
?>
