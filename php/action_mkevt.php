<?php
session_start();
require './creds.php';

if (!isset($_SESSION['assign_user_id'])) {
    die("Access denied.");
}

if (!(isset($_POST['name']) && isset($_POST['date']))) {
    die("Improper information given!");
}

$creds = new Creds();

if ($creds->is_development()) {
    echo "Credentials initialized.<br>";
    echo "Username: " . $_SESSION['assign_user_name'] . "<br>";
}

$conn = new mysqli($creds->get_host(), $creds->get_username(), $creds->get_password(), $creds->get_database());

if ($conn->connect_error) {
    if ($creds->is_development()) {
        die("Connection to database failed: " . $conn->connect_error);
    } else {
        die("Error connecting to database! Please contact an admin!");
    }
}

if ($creds->is_development()) {
    echo "MySQL Connection Successful!<br>";
}

$description = $_POST['description'] ?? null;

$stmt = $conn->prepare("INSERT INTO `events` (`user_id`, `name`, `description`, `due`) VALUES (?, ?, ?, ?);");
$stmt->bind_param("isss", $uid, $n, $d, $du);

$uid = $_SESSION['assign_user_id'];
$n = $_POST['name'];
$d = $description;
$du = $_POST['date'];

$stmt->execute();

echo "success";
