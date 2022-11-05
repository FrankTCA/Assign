<?php
session_start();
require './creds.php';

if (!isset($_SESSION["assign_user_id"])) {
    die("Not logged in!");
}

$creds = new Creds();

if ($creds->is_development()) {
    echo "Credentials initialized.<br>";
    echo "Username: " . $_SESSION["assign_user_name"] . "<br>";
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

$stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$stmt->bind_param("i", $ses);

$ses = $_SESSION['assign_user_id'];
$stmt->execute();
$result = $stmt->get_result();

while ($row = mysqli_fetch_assoc($result)) {
    if ($creds->is_development()) {
        echo "User account exists: ".$row."<br>";
    }
    if ($row['allowed'] == 0) {
        die("Account has not yet been approved!");
    } else {
        if (!(is_null($row['token']))) {
            if ($row['token'] !== "") {
                die("https://infotoast.org/assign/view.php?token=" . $row['token']);
            }
        }
    }
}

try {
    $token_raw = $_SESSION['assign_user_name'] . $creds->get_aes_password() . strval(random_int(0, 999999));
} catch (Exception $e) {
    if ($creds->is_development()) {
        die("Exception: " . $e->getMessage() . "<br>" . $e->getTrace());
    } else {
        die("An exception occured during crytographic random number generation");
    }
}

$token = hash("sha256", $token_raw);

$stmt2 = $conn->prepare("UPDATE `users` SET  `token` = ? WHERE id = ?");
$stmt2->bind_param("si", $token_enc, $uid);

$token_enc = $token;
$uid = $_SESSION['assign_user_id'];
$stmt2->execute();

echo "Success! Copy and paste the following: <a href=\"https://infotoast.org/assign/view.php?token=" . $token . "\">" .
    "https://infotoast.org/assign/view.php?token=" . $token . "</a>";
