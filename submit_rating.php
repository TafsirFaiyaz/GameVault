<?php

include 'db_connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id']);
$rating = intval($_POST['rating']);

// Validate rating value (ensure it's between 1 and 5)
if ($rating < 1 || $rating > 5) {
    die("Invalid rating. Please select a rating between 1 and 5.");
}

// Use prepared statements to prevent SQL injection
if ($stmt = $conn->prepare("SELECT * FROM ratings WHERE user_id = ? AND game_id = ?")) {
    $stmt->bind_param("ii", $user_id, $game_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update the rating if it exists
        if ($update_stmt = $conn->prepare("UPDATE ratings SET rating = ? WHERE user_id = ? AND game_id = ?")) {
            $update_stmt->bind_param("iii", $rating, $user_id, $game_id);
            $update_stmt->execute();
            $update_stmt->close();
        }
    } else {
        // Insert a new rating
        if ($insert_stmt = $conn->prepare("INSERT INTO ratings (user_id, game_id, rating) VALUES (?, ?, ?)")) {
            $insert_stmt->bind_param("iii", $user_id, $game_id, $rating);
            $insert_stmt->execute();
            $insert_stmt->close();
        }
    }
    $stmt->close();
}

header("Location: game_details.php?game_id=$game_id");
exit();
?>
