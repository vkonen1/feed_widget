<?php
require_once("config.php");
require_once("Database.php");

//page information
$page_title = "Manage Feeds";

//instantiate the database
$db = new Database($config['db_hostname'], $config['db_dbname'], $config['db_username'], $config['db_password'], $config['db_timeout']);

//get the feeds
$feeds = $db->fetchFeeds();
$num_feeds = $db->getNumFeeds();

//html file for the page
require_once("html/manage_feeds.html.php");