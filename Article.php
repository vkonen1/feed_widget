<?php
class Article {
    var $title;
    var $url;
    var $pub_date;
    var $timestamp;

    function Article($article_xml) {
        $this->title = $article_xml->title;
        $this->url = $article_xml->link;
        $this->pub_date = $article_xml->pubDate;
        $this->getTimestamp();
    }

    function getTimestamp() {
        date_default_timezone_set('UTC');

        $day = substr($this->pub_date, 5, 2);
        $month = substr($this->pub_date, 8, 3);
        $month = date('m', strtotime("$month 1 2011"));
        $year = substr($this->pub_date, 12, 4);
        $hour = substr($this->pub_date, 17, 2);
        $min = substr($this->pub_date, 20, 2);
        $second = substr($this->pub_date, 23, 2);
        $timezone = substr($this->pub_date, 26);

        $this->timestamp = mktime($hour, $min, $second, $month, $day, $year);

        if(is_numeric($timezone)) {
            $hours_mod = $mins_mod = 0;
            $modifier = substr($timezone, 0, 1);
            if($modifier == "+"){ $modifier = "-"; } else
            if($modifier == "-"){ $modifier = "+"; }
            $hours_mod = (int) substr($timezone, 1, 2);
            $mins_mod = (int) substr($timezone, 3, 2);
            $hour_label = $hours_mod>1 ? 'hours' : 'hour';
            $strtotimearg = $modifier.$hours_mod.' '.$hour_label;
            if($mins_mod) {
                $mins_label = $mins_mod>1 ? 'minutes' : 'minute';
                $strtotimearg .= ' '.$mins_mod.' '.$mins_label;
            }
            $this->timestamp = strtotime($strtotimearg, $this->timestamp);
        }
    }
}
?>