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

//get the feeds
$feeds = $db->fetchFeeds();

//html file for the page
require_once("html/manage_feeds.html.php");