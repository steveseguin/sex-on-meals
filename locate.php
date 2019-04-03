<?php

//$cmd . " > /dev/null &");
$id=0;
$location = "";
array_shift($argv);
if (count($argv) == 0) {
	die('FAIL: no userID given');
}
else {
	$id = intval($argv[0]);
}
if (!$id){
	die("User 0 not allowed!");
}

$con = mysql_connect("localhost","root","bitnami");
if (!$con){
        die('Could not connect: ' . mysql_error());
}
mysql_select_db("sexonmeals", $con);
$output = mysql_query("SELECT * FROM addresses WHERE userid = '$id' ORDER BY addressstatus, id LIMIT 1");
if ($row =  mysql_fetch_assoc($output)){
	$address = $row['shiptostreet'].", ".$row['shiptocity'].", ".$row['shiptostate'].", ".$row['shiptozip'].", ".$row['shiptocountryname'];
        $address = urlencode($address);
	$fullurl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".$address;
	$string = file_get_contents($fullurl); // get json content
	$jn = json_decode($string, true); //json decoder

	echo $jn['results'][0]['geometry']['location']['lat']; // get lat for json
	echo ":";
	echo $jn['results'][0]['geometry']['location']['lng']; // get ing for json

	$location = $row['shiptocity'].", ".$row['shiptostate']; 
	$lat = deg2rad($jn['results'][0]['geometry']['location']['lat']);
	$lon = deg2rad($jn['results'][0]['geometry']['location']['lng']);
}
else {
	$output = mysql_query("SELECT ipaddress FROM log WHERE success = '$id' ORDER BY id DESC LIMIT 1");
	if ($row =  mysql_fetch_assoc($output)){
		$ipaddress = $row['ipaddress'];	
	}
	else {
		$output = mysql_query("SELECT ipaddress FROM users WHERE id = '$id' LIMIT 1");
		if ($row =  mysql_fetch_assoc($output)){
			$ipaddress = $row['ipaddress'];
		}
		else {
			die('No location could be determined');
		}
	}
	$fullurl = "http://api.ipinfodb.com/v3/ip-city/?key=8ee1a8375644c446eba813706b8e21fb6e29da490652cb2df12e3c1598004787&format=json&ip=".$ipaddress;
	$string = file_get_contents($fullurl);
	$jn = json_decode($string, true); //json decoder
	
	echo $jn['latitude'].":".$jn['longitude'];
	$location = ucwords(strtolower($jn['cityName']." ".$jn['regionName']));
	echo " --  ".$location."   ";
	$lat =  deg2rad($jn['latitude']);
	$lon = deg2rad($jn['longitude']);
}
mysql_query("UPDATE users SET latitude='$lat', longitude='$lon', location='$location' where id = '$id' limit 1");
?>
