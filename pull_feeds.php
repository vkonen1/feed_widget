<?php
require_once("config.php");
require_once("Database.php");
require_once("Feed.php");

//instantiate the database
$db = new Database($config['db_hostname'], $config['db_dbname'], $config['db_username'], $config['db_password'], $config['db_timeout']);

//get the feeds
$feeds = $db->fetchFeeds();
$num_feeds = $db->getNumFeeds();

$feed_objects = array();

foreach ($feeds as $feed) {
    array_push($feed_objects, new Feed($feed['feed_url']));
}

var_dump($feed_objects);