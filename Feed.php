<?php
require_once("Article.php");

class Feed {
    var $url;
    var $content;
    var $xml;

    var $articles;

    var $valid;

    function Feed($url) {
        $this->url = $url;
        //gets the content for the rss feed and converts to xml
        $this->getFeedContent();
        //grab the articles from the feed
        $this->getArticles();
    }

    function getFeedContent() {
        $timeout = 5;

        //curl for the rss feed content
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $this->url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $this->content = curl_exec($crl);
        curl_close($crl);

        if (empty($this->content)) {
            $this->valid = false;
        } else {
            //convert to xml
            $this->xml = new SimpleXmlElement($this->content);
            $this->valid = true;
        }
    }

    function getArticles() {
        if ($this->valid) {
            $this->articles = array();
            foreach ($this->xml->channel->item as $article) {
                $article_object = new Article($article);
                array_push($this->articles, $article_object);
            }            
        }
    }
}
?>