<?php
class Article {
    var $title;
    var $url;
    var $host;
    var $pub_date;
    var $timestamp;

    var $content;

    var $image_url;
    var $image_dim;

    var $id;
    var $image_type;

    var $local_image_url;
    var $image_padding_tb;
    var $image_padding_lr;

    function Article($article_xml) {
        $this->title = $article_xml->title;
        $this->url = $article_xml->link;
        $parsed_url = parse_url($this->url);
        $this->host = $parsed_url['host'];
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

    //gets the biggest image in the article and stores it on the server in the images dir
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

        //find the largest image
        $this->image_dim = 0;
        foreach ($images as $article_image) {
            //clean the url
            $image_url = $article_image->getAttribute('src');

            //not an absolute path to the image
            if (strpos($image_url, "//") !== 0 && strpos($image_url, "http") !== 0) {
                $image_url = "http://" . $this->host . $image_url;
            }

            //need to add the protocol
            if (strpos($image_url, "//") === 0) {
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

        //store that image
        $split_url = explode(".", $this->image_url);
        $this->image_type = $split_url[count($split_url) - 1];

        //default to png
        if ($this->image_type != "jpg" && $this->image_type != "png" && $this->image_type != "gif") {
            $this->image_type = "png";
        }

        //save the image
        $ch = curl_init($this->image_url);
        $fp = fopen('images/'.$this->id.'.'.$this->image_type, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        //resize the image
        $image = new Imagick('images/'.$this->id.'.'.$this->image_type);
        $image->resizeImage(250, 150, Imagick::FILTER_LANCZOS, 1, TRUE);
        $image->writeImage('images/'.$this->id.'.'.$this->image_type);

        //get the geometry and determine necessary padding
        $image_geometry = $image->getImageGeometry();
        if ($image_geometry['height'] < 150) {
            $this->image_padding_tb = (150 - $image_geometry['height']) / 2;
        } else {
            $this->image_padding_tb = 0;
        }
        if ($image_geometry['width'] < 250) {
            $this->image_padding_lr = (250 - $image_geometry['width']) / 2;
        } else {
            $this->image_padding_lr = 0;
        }

        //clean up
        $image->destroy();

        //establish the absolute url for the image on the server
        $this->local_image_url = "http://" . $_SERVER['SERVER_NAME'] . "/feed_widget/images/" . $this->id . "." . $this->image_type;

        //print the title and chosen image for debug purposes
        echo $this->title . "<br />";
        echo $this->pub_date . "<br />";
        echo $this->image_url . "<br /><br />";
    }

    function setId($id) {
        $this->id = $id;
    }
}
?>