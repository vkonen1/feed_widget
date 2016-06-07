<?php
require_once("config.php");
require_once("Database.php");

//instantiate the database
$db = new Database($config['db_hostname'], $config['db_dbname'], $config['db_username'], $config['db_password'], $config['db_timeout']);

if (!empty($_GET["feed_id"])) {
    $feed_id = $_GET["feed_id"];

    //remove it from the database
    $db->removeFeed($feed_id);
}

//redirect back to manage
header("Location: manage_feeds.php");
exit;
?>