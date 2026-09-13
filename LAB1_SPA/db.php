<?php

// I am connecting my spa website to the MySQL server
// provided for me by the school.

$host = "localhost";
$db_user = "maame.kome-mensah";
$db_pass = "purple300";
$db_name = "ecommerce_2026A_maame_kome-mensah";

// I am creating the connection between my website and MySQL.
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// I want the website to stop if the database connection fails.
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// I am using UTF-8 so names and other text display correctly.
$conn->set_charset("utf8mb4");

?>