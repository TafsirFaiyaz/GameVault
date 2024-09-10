<?php
session_start();
$conn = mysqli_connect('localhost', 'Tafsir', '', 'gamevault');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id']);
$rating = intval($_POST['rating']);

// Check if the user has already rated the game
$check_sql = "SELECT * FROM ratings WHERE user_id = $user_id AND game_id = $game_id";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    // User has already rated, update the rating
    $update_sql = "UPDATE ratings SET rating = $rating WHERE user_id = $user_id AND game_id = $game_id";
    $conn->query($update_sql);
} else {
    // Insert a new rating
    $insert_sql = "INSERT INTO ratings (user_id, game_id, rating) VALUES ($user_id, $game_id, $rating)";
    $conn->query($insert_sql);
}

header("Location: game_details.php?game_id=$game_id");
exit();
?>
