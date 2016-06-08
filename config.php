<?php
//database connection configuration
$config['db_hostname'] = "localhost";
$config['db_dbname'] = "feed_widget";
$config['db_username'] = "feedwidgetacc";
$config['db_password'] = "feedwidgetpass";
$config['db_timeout'] = 60;

/*number of articles that pull_feeds.php will pull images from and generate
the cached file for */
$config['num_articles'] = 8;