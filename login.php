<?php

    session_start();


    $p = 6;
    $continue = 0;
    $id = 0;
    if (isset($_SESSION['id'])){
        unset($_SESSION['id']);
	$id=-1;
    }
    elseif (isset($_POST['login'])){
	$continue = 1;
	/////////// email
        if (!isset($_POST['email']) || !strlen(check_input($_POST['email']))){
                $emaile = "<div class='error'>You must enter your email.</div>";
		$continue = 0 ;
        }
	else {
		$email = check_input($_POST['email']);
	}
	////////// password
        if (!isset($_POST['upass'])  || strlen(check_input($_POST['upass']))<1){
                $upasse = "<div class='error'>You must enter a password.</div>";
                $continue = 0 ;
        }
	else {
		$pass = check_input($_POST['upass']);
	}
	if ($continue ==1 ){
	        $con = mysql_connect("localhost","root","bitnami");
	        if (!$con){
       	         	die('Could not connect: ' . mysql_error());
       	 	}
        	else {
                	mysql_select_db("sexonmeals", $con);
			$output = mysql_query("SELECT * FROM users WHERE email = '$email' LIMIT 1");
                	if (!$output) {
				$continue = 0;
				die('DATABASE ERROR');
			}
                	else {
				$result = mysql_fetch_assoc($output);
		  		$Tod = strval(date('D-M-Y, G:i:s'));
                		$ipaddress=$_SERVER["REMOTE_ADDR"];
				$salt = $result['salt'];
				$salted = $pass + $salt;
				$hashed = hash('sha512', $salted);
				$uid = $result['id'];
				if ($hashed == $result['hash']){  // SUCCESS
					$continue = 1;
                                        mysql_query("INSERT INTO log (email, date, ipaddress, success) VALUES ('$email', '$Tod', '$ipaddress', '$uid')");
				}
				else {
					unset($_SESSION['id']);
					$continue = 0;
					mysql_query("INSERT INTO log (email, password, date, ipaddress, success) VALUES ('$email', '$pass', '$Tod', '$ipaddress', '0')");
					die('Incorrect Login Information. This failed attempted has been logged');
				}
				$_SESSION['sex']=$result['gender'];
				$_SESSION['id'] = $result['id'];
				$_SESSION['username'] =  $result['username']; 
				$_SESSION['lon'] = $result['longitude'];
				$_SESSION['lat'] = $result['latitude']; 
				header("Location: ../");
				mysql_close($con);
				die('logged in');
			}
		}
		mysql_close($con);
	}
    }



function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

    include("header.php");
    if ($id==-1){
	$id = 0;
        echo "<br /><h2>Logged Out</h2><br />";
    }
?>
<br />
<h3>Login</h3>
<form id="loginForm" action="login" method="post">

<table>
  <tbody>
  <tr>
    <td><label for="email">Email:</label></td>
    <td><div class="input-container"><input name="email" id="email" type="text" /><?php echo $emaile; ?></div></td>
  </tr>
  <tr>
    <td><label for="upass">Password:</label></td>
    <td><div class="input-container"><input name="upass" id="upass" type="password" /><?php echo $upasse; ?></div></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <input type="hidden" name="login" id="login" value="true" />
  <td><input type="submit" class="greenButton" value="Login" />
  </td>
  </tr>
  </tbody>
</table>
</form>
<?php include("footer.php"); ?>
