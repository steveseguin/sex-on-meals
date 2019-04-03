<?php

        if (isset($_GET)){
                $address=array_keys($_GET);
                $address=$address[0];

        $address = urlencode($address);
        //$fullurl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".$address;
       	$fullurl = "http://maps.googleapis.com/maps/api/staticmap?center=".$address."&zoom=13&size=600x300&maptype=roadmap&sensor=false";
	header("Content-type: image/jpeg");
	$string = file_get_contents($fullurl); // get json content
        echo $string;
	//$jn = json_decode($string, true); //json decoder

        //echo $jn['results'][0]['geometry']['location']['lat']; // get lat for json
        //echo ":";
        //echo $jn['results'][0]['geometry']['location']['lng']; // get ing for json

}




?>
