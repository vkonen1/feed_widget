<?php
class Database {
    var $db;

    function Database($hostname, $dbname, $username, $password, $timeout) {
        $db_str = "mysql:host=$hostname;dbname=$dbname;charset=utf8mb4";
        $db = new PDO($db_str, $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $db->setAttribute(PDO::ATTR_TIMEOUT, $timeout);
    }

    //queries for all of the feeds and returns them as an associative array
    function fetchFeeds() {
        $feeds = array();
        $result = $this->db->query("SELECT * FROM feeds");
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            array_push($feeds, $row);
        }
        return $feeds;
    }

    //inserts a feed into the database if it does not exist
    function insertFeed($url) {
        //check if it exists and insert if it does not
        $result = $this->db->query("SELECT COUNT(*) FROM feeds WHERE feed_url = '$url'");
        if ($result->fetchColumn == 0) {
            $query = $this->db->prepare("INSERT INTO feeds (feed_id, feed_url) VALUES (?, ?)");
            $query->bindParam(1, NULL);
            $query->bindParam(2, $url);
            $query->execute();
            $this->db->commit();
        }
    }
}
?>