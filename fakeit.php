<?php
//error_reporting(E_ALL);
$con = mysql_connect("localhost","root","bitnami");
     if (!$con){
                        die('Could not connect: ' . mysql_error());
                }
mysql_select_db("sexonmeals");
$xx=0;
while (1==1){
$xx++;
$yy = strval(57452712 + $xx);

$HTML = file_get_contents('http://www.pof.com/viewprofile.aspx?profile_id='.$yy);
if (!$HTML){
	continue;
}
$doc = new DOMDocument();
$doc->loadHTML($HTML);
$p6="";
$p5="";
$p4="";
$p3="";
$p2="";
$p1="";
$p0="";
////////////// profile photo
foreach ($doc->getElementsByTagName('img') as $img){
	$img = utf8_decode($doc->saveXML($img));
	$img = explode("showtrail(", $img);
	if (count($img)<2){
		echo ".";
		continue;
	}
	$img = $img[1];
	$img = explode(",225", $img);
	$img = $img[0];
	$img = trim($img,"'");
	$img2 = explode("/", $img);
	$count = count($img2)-1;
	//$img2 = $img2[$count];
	$img2 = md5($img).".jpg";
	if ($count>2){
	
	$cmd = "wget -O /opt/bitnami/apache2/htdocs/sexonmeals/photos/$img2 $img";
	@exec($cmd);
	$p6 = $p5;
	$p5 = $p4;
	$p4 = $p3;
	$p3 = $p2;
	$p2 = $p1;
	$p1 = $p0;
	$p0 = $img2;
	}
		/////// NEED TO DOWNLOAD IMAGES TO THE RIGHT FODLER AND TRIM THEIR NAMES
}
if ($p0){
//	echo "!";
  //      $cmd = 'sudo cp /opt/bitnami/apache2/htdocs/sexonmeals/tmp/'.$p0.' /opt/bitnami/apache2/htdocs/sexonmeals/tmp/t_'.$p0.' & sudo mogrify -resize 153x140">" /opt/bitnami/apache2/htdocs/sexonmeals/tmp/t_'.$p0;
//	@exec($cmd);
}else {
	continue;
}
echo $yy."\n";
$x=0;
$long ='';
foreach ($doc->getElementsByTagName('span') as $img){
	if ($img->parentNode->tagName == "div" && $x==0){
        	$img = utf8_decode($doc->saveXML($img));
        	$img = explode("size14\">", $img);
        	$img = $img[1];
        	$img = explode("</span>", $img);
        	$img = $img[0];
		$img = trim($img);
		if ($img) {$x++;}
        	$long =  check_input($img);
	}
}
$x=0;
$city = '';
foreach ($doc->getElementsByTagName('td') as $img){
	$img = utf8_decode($doc->saveXML($img));
        if (strpos($img,'City') && $x==0){
		$x=1;	
	}
	elseif ($x==1){
		$x++;
                $img = explode("txtGrey size15\">", $img);
                $img = $img[1];
                $img = explode("</td>", $img);
                $img = $img[0];
                $city =  check_input($img);
        }
}
$username ='';
$short = '';
foreach ($doc->getElementsByTagName('span') as $img){
        if ($img->parentNode->tagName == "div"){
                $img = utf8_decode($doc->saveXML($img));
                $img = explode("headline txtBlue size28\">", $img);
                $img = $img[1];
                $img = explode(":", $img);
		if (!$username){
                        $username = trim($img[0]);
			$username = explode(";",$username);
                	$username = trim($username[1]);

                        $username = explode(" ",$username);
                        $username = trim($username[0]);

			//echo $username;

                $img = $img[1];
                $img = explode("\"</span>", $img);
                $img = $img[0];
                $short = check_input(trim($img));

		}
        }
}
$year = '';
$gender = '';
$age ='';
$address ='';
$fullurl='';
$lat ='';
$lon='';
////////////////// AGE and Gender
foreach ($doc->getElementsByTagName('td') as $img){
        if ($img->parentNode->tagName == "tr"){
                $img = utf8_decode($doc->saveXML($img));
		if (strpos($img,'year old')){
                	$img = explode("txtGrey size15\">", $img);
               	 	$img = $img[1];
               	 	$img = explode("year old", $img);
                	$age = trim($img[0]);
			$age=ereg_replace("[^0-9]", "", $age);
			$age = (intval($age)-130013)/100;
			$gender =  explode(",", $img[1]);
                	$gender = trim($gender[0]);
			if (strpos($gender,"Woman")){$gender =2;}
			elseif (strpos($gender,"Man")){$gender =1;}
			else {$gender = 0;}
			$year = 2013-intval($age);
		}
        }
}
	//echo $city;
        $address = urlencode($city);
        $fullurl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".$address;
        $string = file_get_contents($fullurl); // get json content
        $jn = json_decode($string, true); //json decoder

        //echo $jn['results'][0]['geometry']['location']['lat']; // get lat for json
        //echo ":";
        //echo $jn['results'][0]['geometry']['location']['lng']; // get ing for json

        $lat = deg2rad($jn['results'][0]['geometry']['location']['lat']);
        $lon = deg2rad($jn['results'][0]['geometry']['location']['lng']);

		$out = mysql_query("INSERT INTO users (username, email, hash, date, birthday, birthyear, birthmonth, salt, ipaddress, verified, thumbnail, gender, p0, p1, p2, p3, p4, p5, p6, short, longd, longitude, latitude, location, poflink) VALUES ('$username', '', '111', '$date', '1', '$year', '1', '1234', '1.1.1.1', '0', '0', '$gender', '$p0', '$p1', '$p2', '$p3', '$p4', '$p5', '$p6', '$short', '$long', '$lon', '$lat','$city', '$yy')");	
		if (!$out){
			echo mysql_error();
		}
		$id = mysql_insert_id();
		///$cmd = "mkdir /opt/bitnami/apache2/htdocs/sexonmeals/photos/$id"; ;
		//@exec($cmd);
		//$cmd = "mv /opt/bitnami/apache2/htdocs/sexonmeals/tmp/* /opt/bitnami/apache2/htdocs/sexonmeals/photos/$id/";
		//@exec($cmd);

}
function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    //$data = htmlspecialchars($data);
    //$data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}
?>


