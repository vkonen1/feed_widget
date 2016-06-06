<?php
class Database {
    var $hostname;
    var $dbname;
    var $username;
    var $password;
    var $timeout;

    var $db;

    function Database($hostname, $dbname, $username, $password, $timeout) {
        $this->hostname = $hostname;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->timeout = $timeout;
        $rs = $this->connect();
        return $rs;
    }

    function connect() {
        $db_str = "mysql:host=$this->hostname;dbname=$this->dbname;charset=utf8mb4";
        $this->db = new PDO($db_str, $this->username, $this->password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->db->setAttribute(PDO::ATTR_TIMEOUT, $this->timeout);
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

    function getNumFeeds() {
        $result = $this->db->query("SELECT COUNT(*) FROM feeds");
        return $result->fetchColumn();
    }

    //inserts a feed into the database if it does not exist
    function insertFeed($url) {
        //check if it exists and insert if it does not
        $result = $this->db->query("SELECT COUNT(*) FROM feeds WHERE feed_url = '$url'");
        if ($result->fetchColumn() == 0) {
            $query = $this->db->prepare("INSERT INTO feeds (feed_url) VALUES (?)");
            $query->bindParam(1, $url);
            $query->execute();
        }
    }

    //removes feed from the database with id
    function removeFeed($id) {
        $this->db->exec("DELETE FROM feeds WHERE feed_id = '$id'");
    }
}
?>