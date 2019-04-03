<?php

 if (isset($_GET['q'])){
	$location = urlencode($_GET['q']);
        if (isset($_GET['lon']) && isset($_GET['lat']) ){
                $lon = urlencode($_GET['lon']*2*3.14159265359);
                $lat = urlencode($_GET['lat']*2*3.14159265359);
        	$fullurl = "https://maps.googleapis.com/maps/api/place/autocomplete/json?sensor=false&key=AIzaSyCCq0qPc8ANOym5n3LQGV6whwA15Jyw1iQ&location=".$lat.",".$lon."&input=".$location."&radius=200";
	}
	else {
		$fullurl = "https://maps.googleapis.com/maps/api/place/autocomplete/json?sensor=false&key=AIzaSyCCq0qPc8ANOym5n3LQGV6whwA15Jyw1iQ&input=".$location;
	}	
	$string = file_get_contents($fullurl); // get json content
	//print_r( $string);
	$jn = json_decode($string, true); //json decoder
	//print_r($jn);
	//print_r( $string);
	$i=0;
	$s1="<option>";
	$s2="</option>";
	$output ="<option disabled selected></option>";
	while ( $jn['predictions'][$i]['description'] ){
		$output .= $s1;
		$output .= ($jn['predictions'][$i]['description']);
		$output .=  $s2;
		$i++; 
	} 
	echo $output;;
        //echo ":";
        //echo $jn['predictions'][0]['geometry']['location']['lng']; // get ing for json

}




?>
