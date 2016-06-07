<?php
class Feed {
    var $url;
    var $content;
    var $xml;

    function Feed($url) {
        $this->url = $url;
        $this->getFeedContent();
    }

    function getFeedContent(){
        $timeout = 5;

        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $this->url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $this->content = curl_exec($crl);
        curl_close($crl);

        $this->xml = new SimpleXmlElement($this->content);
    }
}
?>