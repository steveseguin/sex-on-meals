<?php

        session_start();
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
        else {
                header("Location: login");
                die();
        }


	if (isset($_POST['amount'])){
		$pay = intval(check_input($_POST['amount']));
		if ($pay < 1){
			die('You must enter at least 1$');
		}
		elseif ($pay > 10000){
			die('The maximum single payment you can make is $10000 USD.');
		}
	}
	else {die('No payment deteceted');}

function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

require_once('./class/paypal.php');
require_once('./class/httprequest.php');

        $r = new PayPal();
        $ret = ($r->doExpressCheckout($pay, 'Verification Payment', $id));
	echo "ERROR: An unexpected error occurred.  Please contact support@sexonmeals.com for help.<br /><br />";

?>
