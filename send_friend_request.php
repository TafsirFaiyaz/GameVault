<?php
include "db_connect.php";// Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_SESSION['user_id']; // Current user ID
    $receiver_id = $_POST['receiver_id']; // User to whom the request is being sent

    // Check if a friend request already exists
    $check_query = "SELECT * FROM friends_requests WHERE sender_id = ? AND receiver_id = ? AND status = 0";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $sender_id, $receiver_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Friend request already sent.";
    } else {
        // Insert a new friend request
        $insert_query = "INSERT INTO friends_requests (sender_id, receiver_id, status) VALUES (?, ?, 0)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $sender_id, $receiver_id);
        if ($stmt->execute()) {
            echo "Friend request sent!";
        } else {
            echo "Error sending friend request.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
