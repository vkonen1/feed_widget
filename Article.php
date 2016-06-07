<?php
class Article {
    var $title;
    var $url;
    var $pub_date;
    var $timestamp;

    var $content;

    var $image_url;
    var $image_dim;

    function Article($article_xml) {
        $this->title = $article_xml->title;
        $this->url = $article_xml->link;
        $this->pub_date = $article_xml->pubDate;
        //generate the unix timestamp from the article pubDate
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

        if (is_numeric($timezone)) {
            $hours_mod = $mins_mod = 0;

            $modifier = substr($timezone, 0, 1);
            if ($modifier == "+") {
                $modifier = "-";
            } else if ($modifier == "-") {
                $modifier = "+";
            }

            $hours_mod = (int)substr($timezone, 1, 2);
            $mins_mod = (int)substr($timezone, 3, 2);
            $hour_label = $hours_mod>1 ? 'hours' : 'hour';
            $strtotimearg = $modifier.$hours_mod.' '.$hour_label;
            if ($mins_mod) {
                $mins_label = $mins_mod > 1 ? 'minutes' : 'minute';
                $strtotimearg .= ' ' . $mins_mod . ' ' . $mins_label;
            }
            $this->timestamp = strtotime($strtotimearg, $this->timestamp);
        }
    }

    //gets the biggest image in the article
    function getImage() {
        $timeout = 5;

        //curl for the article content
        $crl = curl_init();
        curl_setopt($crl, CURLOPT_URL, $this->url);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $this->content = curl_exec($crl);
        curl_close($crl);

        $dom = new DOMDocument();
        //ignore the parsing errors
        libxml_use_internal_errors(true);
        //parse the html
        $dom->loadHTML($this->content);
        //get the img tags
        $images = $dom->getElementsByTagName('img');

        $this->image_dim = 0;
        foreach ($images as $article_image) {
            //clean the url
            $image_url = $article_image->getAttribute('src');
            if (strpos($image_url, "http") !== 0) {
                $image_url = "http:" . $image_url;
            }

            //check if its a valid image
            if (exif_imagetype($image_url)) {
                //get the images size
                $image_size = getimagesize($image_url);
                $image_width = $image_size[0];
                $image_height = $image_size[1];

                //check if this image is biggest
                if ($image_width * $image_height > $this->image_dim) {
                    $this->image_url = $image_url;
                    $this->image_dim = $image_width * $image_height;
                }
            }
        }

        echo $this->title . "<br />";
        echo $this->image_url . "<br /><br />";
    }
}
?>