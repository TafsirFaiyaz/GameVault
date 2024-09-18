<?php
include "db_connect.php"; // Database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $sender_id = $_POST['sender_id'];
    $current_user_id = $_SESSION['user_id']; // Receiver ID
    $action = $_POST['action'];

    // If the user accepts the friend request
    if ($action === 'accept') {
        // Insert into friends table
        $insert_query = "INSERT INTO friends (user_id, friend_id) VALUES (?, ?), (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("iiii", $current_user_id, $sender_id, $sender_id, $current_user_id);
        $stmt->execute();

        // Update the friend request status to accepted
        $update_query = "UPDATE friends_requests SET status = 1 WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();

        echo "Friend request accepted!";
    } elseif ($action === 'reject') {
        // Update the friend request status to rejected
        $update_query = "UPDATE friends_requests SET status = -1 WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();

        echo "Friend request rejected!";
    }

    $stmt->close();
    $conn->close();
}
?>
