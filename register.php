<?php

    session_start();
   
    $continue = 0; 
    if (isset($_POST['register'])){
	$continue = 1;
	/////////// user name
        if (!isset($_POST['uname']) || !strlen(check_input($_POST['uname']))){
                $unamee = "<div class='error'>You must enter a display name.</div>";
		$continue = 0 ;
        }
	elseif ($_POST['uname'] != check_input($_POST['uname'])){
                $unamee = "<div class='error'>Username contains invalid characters.</div>";
                $continue = 0 ;
        }
	else {
		$uname = check_input($_POST['uname']);
	}
	////////// password
        if (!isset($_POST['upass'])  || strlen(check_input($_POST['upass']))<1){
                $upasse = "<div class='error'>You must enter a password.</div>";
                $continue = 0 ;
        }
        elseif ($_POST['upass'] != check_input($_POST['upass'])){
                $unamee = "<div class='error'>Your password contains invalid characters.</div>";
                $continue = 0 ;
        }
        elseif ( strlen(check_input($_POST['upass']))<8){
                $upasse = "<div class='error'>Your password must be 8-characters or longer.</div>";
                $continue = 0 ;
        }
        elseif (!isset($_POST['cpass']) || strlen(check_input($_POST['cpass']))<1){
                $cpasse = "<div class='error'>You must enter the confirmation password.</div>";
                $continue = 0 ;
        }
        elseif (($_POST['cpass'])!=($_POST['upass'])){
                $upasse = "<div class='error'>The passwords do not match.</div>";
                $continue = 0 ;
        }
	else {
		$pass = check_input($_POST['upass']);
	}
	/////////// last name
        if (isset($_POST['lname']) && strlen(check_input($_POST['lname']))){
        	$lname=check_input($_POST['lname']);
	}
	///////// first name
        if (isset($_POST['fname']) && strlen(check_input($_POST['fname']))){
        	$fname=check_input($_POST['fname']);
	}
	/////////// email
        if (!isset($_POST['email']) || !strlen(check_input($_POST['email']))){
                $emaile = "<div class='error'>You must enter your email.</div>";
                $continue = 0 ;
        }
        elseif ($_POST['email'] != check_input($_POST['email'])){
                $unamee = "<div class='error'>Your email contains invalid characters.</div>";
                $continue = 0 ;
        }
        else {
                $email = check_input($_POST['email']);
        }
	//////////// gender
        if (!isset($_POST['sex-select']) || !(($_POST['sex-select']==1) || ($_POST['sex-select']==2))){
                $gendere = "<div class='error'>You must enter your gender.</div>";
                $continue = 0 ;
        }
        else {
                $gender = check_input($_POST['sex-select']);
        }
	//////////// birthdate
        if (!isset($_POST['day']) || !isset($_POST['month']) || !isset($_POST['year'])){
                $daye = "<div class='error'>You must select your full date of birth.</div>";
                $continue = 0 ;
        }
	elseif (intval($_POST['day']>31) || intval($_POST['month']>12) || intval($_POST['year']>2012) ){
		$daye = "<div class='error'>error: Birthday out of bounds. Please re-select.</div>";
                $continue = 0 ;
	}
        elseif (intval($_POST['day']<1) || intval($_POST['month']<1) || intval($_POST['year']<1920) ){
                $daye = "<div class='error'>You must select your full date of birth.</div>";
                $continue = 0 ;
        }
        else {
                $day = check_input(intval($_POST['day']));
		$month = check_input(intval($_POST['month']));
                $year = check_input(intval($_POST['year']));
	}

	if ($continue ==1 ){
	        $con = mysql_connect("localhost","root","bitnami");
	        if (!$con){
       	         	die('Could not connect: ' . mysql_error());
       	 	}
        	else {
                	mysql_select_db("sexonmeals", $con);
			$output = mysql_query("SELECT id FROM users WHERE username = '$uname' LIMIT 1");
			$output2 =  mysql_query("SELECT id FROM users WHERE email = '$email' LIMIT 1");
                	if (!$output || !$output2) {
				$continue = 0;
				die('DATABASE ERROR');
			}
			elseif (mysql_fetch_array($output)){
                   		$unamee = "<div class='error'>Username exists already.</div>";
				$continue = 0;
                	}
			elseif (mysql_fetch_array($output2)){
                                $emaile = "<div class='error'>This email is already registered.</div>";
                                $continue = 0;
                        }
                	else {
		  		$Tod = strval(date('D-M-Y, G:i:s'));
                		$ipaddress=$_SERVER["REMOTE_ADDR"];
				$salt = rand(10000000,99999999);
				$salted = $pass + $salt;
				$hashed = hash('sha512', $salted);
				mysql_query("INSERT INTO users (username, firstname, lastname, email, hash, date, birthday, birthmonth, birthyear, salt, ipaddress, gender) VALUES ('$uname', '$fname', '$lname', '$email', '$hashed', '$Tod', '$day', '$month', '$year', '$salt', '$ipaddress', '$gender')");
				$id = mysql_insert_id();
				$_SESSION['id'] = $id;
				$_SESSION['sex']=$gender;
				$_SESSION['username'] = $uname;
	//////// UPDATES LONG/LAT 
        $cmd = "php locate.php ".$id." > /dev/null &";
        @exec($cmd);  
				header("Location: edit?new");
				mysql_close($con);
				die("Registered.");
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

?>
<br />
<h3>Register - it's FREE!</h3>
<form id="regForm" action="register" method="post">

<table>
  <tbody>
  <tr>
    <td><label for="uname">Display Name*:</label></td>
    <td><div class="input-container"><input name="uname" id="uname" type="text" value="<?php echo $uname; ?>"/><?php echo $unamee; ?></div></td>
  </tr>
  <tr>
    <td><label for="upass">Password*:</label></td>
    <td><div class="input-container"><input name="upass" id="upass" type="password"  onBlur="if (this.value.length<8){document.getElementById('pas1').innerHTML='Password must be at least 8 characters long.';} else {document.getElementById('pas1').innerHTML='';}" /><div id='pas1'></div><?php echo $upasse; ?></div></td>
  </tr>
  <tr>
    <td><label for="cpass">Confirm Password*:</label></td>
    <td><div class="input-container"><input name="cpass" id="cpass" type="password" onBlur="if (this.value!=document.getElementById('upass').value){document.getElementById('pas2').innerHTML='Passwords do not match';}else {document.getElementById('pas1').innerHTML='';}"/><div id='pas2'></div><?php echo $cpasse; ?></div></td>
  </tr>
  <tr>
    <td><label for="fname">First Name:</label></td>
    <td><div class="input-container"><input name="fname" id="fname" type="text" value="<?php echo $fname; ?>"/></div></td>
  </tr>
  <tr>
    <td><label for="lname">Last Name:</label></td>
    <td><div class="input-container"><input name="lname" id="lname" type="text" value="<?php echo $lname; ?>"/></div></td>
  </tr>
  <tr>
    <td><label for="email">Your Email*:</label></td>
    <td><div class="input-container"><input name="email" id="email" type="text" value="<?php echo $email; ?>"/><?php echo $emaile; ?></div></td>
  </tr>
  <tr>
    <td><label for="sex-select">I am*:</label></td>
    <td>
    <div class="input-container">
    <select name="sex-select" id="sex-select">
    <option value="0">Select Sex:</option>
    <option value="1" <?php if ($gender==1){echo "SELECTED";} ?>>Female</option>
    <option value="2"  <?php if ($gender==2){echo "SELECTED";} ?>>Male</option>
    </select>
	<?php echo $gendere; ?>
    </div>
    
    </td>
  </tr>
  <tr>
    <td><label>Birthday*:</label></td>
    <td>
    <div class="input-container">
    <select name="month"><option value="0">Month:</option>
	<option value="1" <?php if ($month==1){echo "SELECTED";} ?>>January</option>
	<option value="2" <?php if ($month==2){echo "SELECTED";} ?>>Febuary</option>
        <option value="3" <?php if ($month==3){echo "SELECTED";} ?>>March</option>
        <option value="4" <?php if ($month==4){echo "SELECTED";} ?>>April</option>
        <option value="5" <?php if ($month==5){echo "SELECTED";} ?>>May</option>
        <option value="6" <?php if ($month==6){echo "SELECTED";} ?>>June</option>
        <option value="7" <?php if ($month==7){echo "SELECTED";} ?>>July</option>
        <option value="8" <?php if ($month==8){echo "SELECTED";} ?>>August</option>
        <option value="9" <?php if ($month==9){echo "SELECTED";} ?>>September</option>
        <option value="10" <?php if ($month==10){echo "SELECTED";} ?>>October</option>
        <option value="11" <?php if ($month==11){echo "SELECTED";} ?>>November</option>
        <option value="12" <?php if ($month==12){echo "SELECTED";} ?>>December</option>
    </select>
    <select name="day"><option value="0">Day:</option>
	<?php 
		$i=0;
		while ($i<31){
			$i++;
			if ($day==$i){
				echo '<option value="'.$i.'" SELECTED>'.$i.'</option>';
			}
			else {
				echo '<option value="'.$i.'">'.$i.'</option>';
			}
		} 
	?>
	</select>
	<select name="year"><option value="0">Year:</option>
        <?php
                $i=2012;
                while ($i>1920){
                        $i--;
			if ($year == $i){
                        	echo '<option value="'.$i.'" SELECTED>'.$i.'</option>';
                	}
			else {
				 echo '<option value="'.$i.'">'.$i.'</option>';
			}
		}
        ?>
</select><?php echo $daye; ?>
    </div>
    </td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  <input type="hidden" name="register" id="register" value="true" />
  <td><input type="submit" class="greenButton" value="Sign Up" />
</td>
  </tr>
  
  
  </tbody>
</table>
*required<br /><br />Note: We take your privacy very seriously. We store salted hashes instead of passwords in our database and our staff will never ask for your password. 
<br />Disclaimer: By using this site, you agree that you are at least 18 years of age, that your personal information is accurate, and that you will not hold 'Sex on Meals' or its affliates liable for any reason.
</form>
<?php include("footer.php"); ?>
