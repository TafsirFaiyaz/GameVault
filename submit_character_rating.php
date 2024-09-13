<?php
// Include database connection
include 'db_connect.php';

$character_id = $_POST['character_id'];
$user_id = $_SESSION['user_id'] ; // Example user ID. Replace with session-based or actual user system.
$rating = $_POST['rating'];

// Check if the user already rated the character
$query = "SELECT * FROM character_ratings WHERE character_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $character_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update the existing rating
    $update_query = "UPDATE character_ratings SET rating = ? WHERE character_id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("iii", $rating, $character_id, $user_id);
    $update_stmt->execute();
} else {
    // Insert a new rating
    $insert_query = "INSERT INTO character_ratings (character_id, user_id, rating) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("iii", $character_id, $user_id, $rating);
    $insert_stmt->execute();
}

// Redirect back to the character details page
header("Location: character_details.php?id=" . $character_id);
exit();
?>
