<?php
        session_start();
        $id =0;
        $uid=0;
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
	else {
		header("Location: login");
	}
	
	if (isset($_POST['mTitle']) && isset($_POST['mBody']) && isset($_GET['u'])){
		$mTitle = check_input($_POST['mTitle']);
		$mBody = check_input($_POST['mBody']);
                $uid = check_input($_GET['u']);		

                $Tod = strval(date('D-M-Y, G:i:s'));
                $ipaddress=$_SERVER["REMOTE_ADDR"];

	        $con = mysql_connect("localhost","root","bitnami");
        	if (!$con){
        	         die('Could not connect: ' . mysql_error());
        	}
        	mysql_select_db("sexonmeals", $con);
        	mysql_query("INSERT INTO messages (rid, sid, subject, message, datesent, sip) VALUES ('$uid', '$id', '$mTitle', '$mBody', '$Tod', '$ipaddress')");
		
	
		header("Location: sent");	
                die();
	

	}
        elseif (isset($_GET)){
                $uid=array_keys($_GET);
                $uid=intval(check_input($uid[0]));
        }
	if (isset($_GET['s'])){
		$sub = "Re: ".check_input($_GET['s']);
	}
        if ($uid){}
        elseif (isset($_GET['u']) && intval($_GET['u'])>0){
                $uid = intval(check_input($_GET['u']));
        }
        else {
		include('header.php');
                echo "<h3>No Target User Selected</h3><br /><br />";
		include('footer.php');
                die();
        }
	 
include('header.php');
?>

<h1>Send a Message to user ID <?php echo $uid ?></h1>
<form action='message?u=<?php echo $uid ?>' method='POST'>
	<h4>Subject:</h4>
	<input type='text' id='mTitle' size='100' name='mTitle' style='width:500px;' value='<?php echo urldecode($sub); ?>'/><br />
	<h4>Body:</h4>
	<input type='textbox' id='mBody' name='mBody' style='height:500px;width:700px;' /><br /><br />
	<input type='submit' value='Send!' style='width:200px;height:40px;' />
	<br /><br />
</form>

<?php


function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}


include('footer.php');
?>
