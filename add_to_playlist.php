<?php
session_start();
$conn = mysqli_connect('localhost', 'Tafsir', '', 'gamevault');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id']);

// Check if the user already added the game to their playlist
$check_sql = "SELECT * FROM user_planned_list WHERE user_id = $user_id AND game_id = $game_id";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows == 0) {
    // User hasn't added the game yet, so insert it
    $insert_sql = "INSERT INTO user_planned_list (user_id, game_id) VALUES ($user_id, $game_id)";
    $conn->query($insert_sql);
}

header("Location: game_details.php?game_id=$game_id");
exit();
?>
