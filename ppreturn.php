<?php

require_once('./class/paypal.php'); //when needed
require_once('./class/httprequest.php'); //when needed
include("header.php");


session_start();
if (isset($_SESSION['id'])){
        $userid = $_SESSION['id'];
} else {
	$userid = "0";
	echo "Alert: You are no longer signed in.";
}

    $p = 0;



//use this form for production serverd = 0;
//$r = new PayPal(true);

//Use this form for sandbox tests
$r = new PayPal();

$final = $r->doPayment();

if (check_input($final['ACK']) == 'Success') {

	echo "<BR /><H1>Payment Success!</h1>";
	//print_r($final);

        if (isset($final['AMT'])){
                $payment = check_input($final['AMT']);
        } else { $payment = ""; }

	$final = $r->getCheckoutDetails($final['TOKEN']);

	$con = mysql_connect("localhost","root","bitnami");

        if (!$con){
            echo "OOPS!!  But could not connect to server database to update your account details. Please contact support@sexonmeals.com to inform us that your account needs to be manually updated.";
        }

        $ipaddress=$_SERVER["REMOTE_ADDR"];
        $date = strval(date('D-M-Y, G:i:s'));

        if (isset($final['EMAIL'])){
                $email = check_input($final['EMAIL']);
	} else { $email = ""; }
        if (isset($final['PAYERID'])){
                $payerid = check_input($final['PAYERID']);
        } else { $payerid = ""; }
        if (isset($final['PAYERSTATUS'])){
                $payerstatus = check_input($final['PAYERSTATUS']);
        } else { $payerstatus = ""; }
        if (isset($final['FIRSTNAME'])){
                $firstname = check_input($final['FIRSTNAME']);
        } else { $firstname = ""; }
        if (isset($final['LASTNAME'])){
                $lastname = check_input($final['LASTNAME']);
        } else { $lastname = ""; }
        if (isset($final['COUNTRYCODE'])){
                $countrycode = check_input($final['COUNTRYCODE']);
        } else { $countrycode = ""; }
        if (isset($final['SHIPTONAME'])){
                $shiptoname = check_input($final['SHIPTONAME']);
        } else { $shiptoname = ""; }
        if (isset($final['SHIPTOSTREET'])){
                $shiptostreet = check_input($final['SHIPTOSTREET']);
        } else { $shiptostreet = ""; }
        if (isset($final['SHIPTOCITY'])){
                $shiptocity = check_input($final['SHIPTOCITY']);
        } else { $shiptocity = ""; }
        if (isset($final['SHIPTOSTATE'])){
                $shiptostate = check_input($final['SHIPTOSTATE']);
        } else { $shiptostate = ""; }
        if (isset($final['SHIPTOZIP'])){
                $shiptozip = check_input($final['SHIPTOZIP']);
        } else { $shiptozip = ""; }
        if (isset($final['SHIPTOCOUNTRYCODE'])){
                $shiptocountrycode = check_input($final['SHIPTOCOUNTRYCODE']);
        } else { $shiptocountrycode = ""; }
        if (isset($final['SHIPTOCOUNTRYNAME'])){
                $shiptocountryname = check_input($final['SHIPTOCOUNTRYNAME']);
        } else { $shiptocountryname = ""; }
        if (isset($final['ADDRESSSTATUS'])){
                $addressstatus = check_input($final['ADDRESSSTATUS']);
        } else { $addressstatus = ""; }
        if (isset($final['INVNUM'])){
                $userid = check_input($final['INVNUM']);
        }
        mysql_select_db("sexonmeals", $con);
        mysql_query("INSERT INTO addresses (email, payerid, payerstatus, firstname, lastname, countrycode, shiptoname, shiptostreet, shiptocity, shiptostate, shiptozip, shiptocountrycode, shiptocountryname, addressstatus, payment, userid, date, ipaddress) VALUES ('$email', '$payerid', '$payerstatus', '$firstname', '$lastname', '$countrycode', '$shiptoname', '$shiptostreet', '$shiptocity', '$shiptostate', '$shiptozip', '$shiptocountrycode', '$shiptocountryname', '$addressstatus', '$payment', '$userid', '$date', '$ipaddress')");

	$points = round($payment);
	mysql_query("UPDATE users SET verified = verified + $payment WHERE id = '$userid'");

	$cmd = "php locate.php ".$userid." > /dev/null &";
	@exec($cmd);
/* Details example:
Array
(
    [TOKEN] => EC-46K253307T956310E
    [TIMESTAMP] => 2010-12-12T09:38:01Z
    [CORRELATIONID] => cbf9ed77f3dbe
    [ACK] => Success
    [VERSION] => 52.0
    [BUILD] => 1613703
    [EMAIL] => buyer1_1292145548_per@maly.cz
    [PAYERID] => ZMU92MM4SPBHS
    [PAYERSTATUS] => verified
    [FIRSTNAME] => Test
    [LASTNAME] => User
    [COUNTRYCODE] => US
    [CUSTOM] => 5|USD|
)
*/
}
else
{
	echo "<h2>PAYMENT CANCELLED OR FAILED!!</h2><br /><Br />";
	echo "If this is a mistake, or if you are unable to make the payment, please contact us at:  <b>support@sexonmeals.com</b>";
	echo "<HR /><br />Failed payment reason: ".($final['L_LONGMESSAGE0']);
}
include("footer.php");
function check_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);

    return $data;
}

mysql_close($con);
die();
?>
