# feed_widget

All files must be placed on server root in a directory named (feed_widget)

feed_widget.js
Contains the javascript to pull the cached file and display it to the div with
id feed_widget_container
Need to change the URL here to point to your hosted version

config.php
Database configuration variables (hostname, dbname, username, password, timeout)
Need to change these to yours

feed_widget.sql
A backup of the database for this project is provided

pull_feeds.php
Script to be run as a CRON job that pulls the feeds and generates the cached file
CRON: 0 0 * * * php /var/www/feed_widget/pull_feeds.php
Note: may need to be modified to fit your server configuration

feed_widget_test.html
Test page for the concept

Classes for Database, Feed, and Article encapsulate behavior

manage_feeds.php, add_feed.php, remove_feed.php
Admin panel scripts
manage_feeds.php script to use for managing the feeds in the database

Style is in the css folder

******Permissions NOTE******
Need to create a folder within the folder for the widget called (images) with write
permissions for www-data
(html) folder also needs write permissions for www-data

Need curl and imagick installed for php5 as well