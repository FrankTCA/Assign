<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php
            if (isset($_SESSION["assign_user_id"])) {
                ?>
        <title>Assign Dashboard</title>
        <?php
            } else {
                ?>
        <title>Access denied</title>
        <?php
            }
        ?>
        <script type="text/javascript" src="resources/jquery.min.js"></script>
        <script type="text/javascript" src="resources/dash.js"></script>
        <link rel="stylesheet" href="resources/global.css">
        <meta name="robots" content="noindex,nofollow">
    </head>
    <body>
    <?php
    if (!isset($_SESSION['assign_user_id'])) {
    ?>
    <div class="container" id="notallowed">
        <h1 class="center">Access denied.</h1>
        <p class="center">You are not permitted to access the dashboard at this time.</p>
        <p class="center">Please register here if you haven't already: <a href="register.php">https://infotoast.org/assign/register.php</a></p>
    </div>
    <?php
        } else {
    ?>
    <div class="container" id="allowed">
        <h1 class="center">Welcome back, <?php
            echo $_SESSION['assign_user_name'];
            ?>
        </h1>
        <p class="center">Welcome to the Info Toast Assign Dashboard!</p>
        <p class="center">Here, you can get the display page link, as well as create events.</p>
        <hr>
        <h3>Display page link:</h3>
        <p>Press the button below to retrieve the link for your display page.</p>
        <p>You can put this on your desktop, as your browser homepage, and even put it on a dedicated screen using software like ScreenCloud!</p>
        <button class="linkbtn" id="getLinkBtn">Get Link!</button><br>
        <span class="link" id="link"></span>
        <hr>
        <h3>Create Event:</h3>
        <form>
            <span class="formInfo">Event name: </span><input type="text" name="evtName" id="evtNameBox" placeholder="English Paper"><span class="invalidWarning" id="evtNameWarning">Max 36 characters!</span>
            <span class="formInfo">Description:</span><input type="text" name="descr" id="descrBox" placeholder="Optional">
            <span class="formInfo">Due Date:   </span><input type="text" name="date" id="dateBox" placeholder="mm/dd/yyyy">
        </form>
        <button onclick="onSubmit()" class="submitbtn" id="evtSubmitBtn">Create Event</button>
        <p id="status">You shouldn't see this!</p>
    </div>
    <?php
    }
    ?>
    </body>
</html>
