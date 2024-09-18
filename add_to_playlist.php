<?php
include "db_connect.php";

$user_id = $_SESSION['user_id'];
$game_id = intval($_POST['game_id']);


$check_sql = "SELECT * FROM user_planned_list WHERE user_id = $user_id AND game_id = $game_id";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows == 0) {

    $insert_sql = "INSERT INTO user_planned_list (user_id, game_id) VALUES ($user_id, $game_id)";
    $conn->query($insert_sql);
}

header("Location: game_details.php?game_id=$game_id");
exit();
?>
