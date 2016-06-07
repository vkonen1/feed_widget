<?php
//used to clean post data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

require_once("config.php");
require_once("Database.php");

//instantiate the database
$db = new Database($config['db_hostname'], $config['db_dbname'], $config['db_username'], $config['db_password'], $config['db_timeout']);

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