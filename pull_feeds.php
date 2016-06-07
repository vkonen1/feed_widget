<?php
require_once("config.php");
require_once("Database.php");
require_once("Feed.php");
require_once("Article.php");

//instantiate the database
$db = new Database($config['db_hostname'], $config['db_dbname'], $config['db_username'], $config['db_password'], $config['db_timeout']);

//get the feeds
$feeds = $db->fetchFeeds();
$num_feeds = $db->getNumFeeds();

//build the Feed objects from the rss feed urls
$feed_objects = array();
foreach ($feeds as $feed) {
    array_push($feed_objects, new Feed($feed['feed_url']));
}

var_dump($feed_objects[0]->articles);