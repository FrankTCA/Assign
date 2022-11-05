<?php
session_start();

if (isset($_SESSION['assign_user_id'])) {
    header("Location: dash.php");
} else {
    header("Location: login.html");
}
