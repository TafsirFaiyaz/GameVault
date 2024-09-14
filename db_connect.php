<?php

session_start();
$conn = mysqli_connect('localhost', 'Tafsir', '', 'gamevault');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}