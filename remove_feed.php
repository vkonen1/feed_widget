<?php
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

if (!empty($_GET["feed_id"])) {
    $feed_id = $_GET["feed_id"];

    //remove it from the database
    $db->removeFeed($feed_id);
}

//redirect back to manage
header("Location: manage_feeds.php");
exit;
?>