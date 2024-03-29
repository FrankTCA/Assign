<?php
require 'php/creds.php';
$blocked = false;
if (!(isset($_GET['token']))) {
    $blocked = true;
}

if (!$blocked) {
    $creds = new Creds();

    $conn = new mysqli($creds->get_host(), $creds->get_username(), $creds->get_password(), $creds->get_database());

    if ($conn->connect_error) {
        if ($creds->is_development()) {
            die("Connection to database failed: " . $conn->connect_error);
        } else {
            die("Error connecting to database! Please contact an admin!");
        }
    }

    $stmt = $conn->prepare("SELECT * FROM `users` WHERE `token` LIKE ?;");
    $stmt->bind_param("s", $tok);

    $tok = $_GET['token'];
    $stmt->execute();
    $result = $stmt->get_result();

    $userid = null;
    $username = null;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['allowed'] == 0) {
            $blocked = true;
        } else {
            $userid = $row['id'];
            $username = $row['name'];
        }
    }

    $tz = 'America/New_York';
    $tz_obj = new DateTimeZone($tz);
    $theDate = new DateTime("now", $tz_obj);
    $today = $theDate->format('m/d/o');
    $tom = new DateTime($today . ' + 1 day');
    $tomorrow = $tom->format('m/d/o');
    $dates = Array($today, $tomorrow);

    for ($day = 1; $day < 11; $day += 1) {
        $theDay = new DateTime($tomorrow . '+' . $day . ' days');
        $dayText = $theDay->format('m/d/o');
        $dates[] = $dayText;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="resources/jquery.min.js"></script>
        <meta name="robots" content="noindex,nofollow">
        <?php
            if ($blocked) {
        ?>
        <title>Access denied.</title>
        <link rel="stylesheet" href="resources/global.css">
        <?php
            } else {
        ?>
        <title>Info Toast Assign</title>
        <link rel="stylesheet" href="https://use.typekit.net/avi3kdp.css">
        <link rel="stylesheet" href="resources/view.css">
        <style>
            .bg {
                background-image: url("https://infotoast.org/images/<?php
            echo rand(0, 135);
?>.jpg");
                background-size: 100% 100%;
                width: 100%;
                height: 100%;
            }
        </style>
        <?php
            }
        ?>
    </head>
    <body>
    <?php
    if ($blocked) {
    ?>
        <div class="container" id="notallowed">
            <h1 class="center">Access denied.</h1>
            <p class="center">You are not permitted to access the viewing page at this time.</p>
            <p class="center">Have you supplied a token in the query string?</p>
        </div>
    <?php
    } else {
    ?>
        <div class="bg">
            <h1 class="hello">Hello, <?php
            if (!(isset($username))) {
                echo "Big problem!";
            } else {
                echo $username;
            }
            ?></h1>
            <?php
                foreach ($dates as $day) {
                    $query = $conn->prepare("SELECT * FROM `events` WHERE `user_id` = ? AND `due` LIKE ?;");
                    $query->bind_param("is", $uid, $d);

                    $uid = $userid;
                    $d = $day;
                    $query->execute();
                    $result2 = $query->get_result();
                    $doneyet = false;
                    while ($row = mysqli_fetch_assoc($result2)) {
                        if (!$doneyet) {
                            if ($day == $today) {
                                echo "<span class=\"day\">TODAY</span><br>";
                            } else if ($day == $tomorrow) {
                                echo "<span class=\"day\">TOMORROW</span><br>";
                            } else {
                                $date2 = new DateTime($day);
                                echo "<span class=\"day\">" . $date2->format('l, F j') . "</span><br>";
                            }
                            $doneyet = true;
                        }
                        echo "<div class=\"dateBox\"><span class=\"evtName\">" . $row['name'] . "</span><br>";
                        if (!is_null($row['description'])) {
                            if ($row['description'] != "") {
                                echo "<span class=\"evtDesc\">" . $row['description'] . "</span>";
                            }
                        }
                        echo "</div><br>";
                    }
                }
            ?>
        </div>
    <?php
    }
    ?>
    </body>
</html>
