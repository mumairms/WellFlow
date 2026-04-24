<?php

$host = "sql100.infinityfree.com";
$user = "if0_41739183";
$password = "meumair0403";
$database = "if0_41739183_wellflow";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>