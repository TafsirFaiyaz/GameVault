<?php

session_start();
$conn = mysqli_connect('localhost', 'Tafsir', '', 'gamevault');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>