<?php
class Feed {
    var $url;
    var $content;
    var $xml;

    var $articles;

    function Feed($url) {
        $this->url = $url;
        $this->getFeedContent();
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

        //convert to xml
        $this->xml = new SimpleXmlElement($this->content);
    }

    function getArticles() {
        $this->articles = array();
        foreach ($this->xml->channel->item as $article) {
            array_push($this->articles, new Article($article));
        }
    }
}
?>