<?php
/*
This script should be run as a CRON job as follows
0 0 * * * php /var/www/feed_widget/pull_feeds.php

Queries for all rss feeds enetered in the database
Pulls the content for all of those feeds
Generates an array of articles sorted by timestamp from the pool of feeds
Grabs the images from the first $config['num_articles'] articles specified in the config.php file
Stores these images on the server in the images relative path directory

*/
require_once("config.php");
require_once("Database.php");
require_once("Feed.php");

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

//generate the array of articles from all of the feeds ordered by timestamp desc
$articles = array();
foreach ($feed_objects as $feed_object) {
    foreach ($feed_object->articles as $article_object) {
        $inserted = false;
        for ($i = 0; $i < count($articles); $i++) {
            //insert this article into the array at this position
            if ($article_object->timestamp > $articles[$i]->timestamp) {
                array_splice($articles, $i, 0, $article_object);
                $inserted = true;
                break;
            }
        }

        //append if it wasnt inserted
        if (!$inserted) {
            array_push($articles, $article_object);
        }
    }
}

//debug code to ensure the articles are in the correct order
/*
foreach ($articles as $article) {
    echo $article->title . "<br />" . $article->pub_date . "<br /><br />";
}
*/

//need to pull images for the number of articles desired
$num_articles = $config['num_articles'];
for ($i = 0; $i < $num_articles; $i++) {
    $articles[$i]->getImage();
}

//var_dump($articles[0]->images);