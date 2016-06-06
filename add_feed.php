<?php
//used to clean post data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

require_once("Database.php");

//database connection information
$hostname = "localhost";
$dbname = "feed_widget";
$username = "feedwidgetaccount";
$password = "feedwidgetpass";
//seconds to attempt database connection
$timeout = 60;

//instantiate the database
$db = new Database($hostname, $dbname, $username, $password, $timeout);

if (!empty($_POST['feed_url'])) {
    //clean it up
    $feed_url = test_input($_POST["feed_url"]);

    //insert it into the database
    $db->insertFeed($feed_url);
}

//redirect back to manage
header("Location: manage_feeds.php");
exit;
?>