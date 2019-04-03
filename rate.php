<?php
        session_start();

        $id =0;
	$uid =0;

        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
	else {
		header("Location: ../login"); 
		die();
	}
	$update='';
	if (isset($_POST['rating']) && isset($_SESSION['rid'])){
		$rid = $_SESSION['rid'];
		unset($_SESSION['rid']);
		$rating = intval(check_input($_POST['rating']));
                if ($rating == 1 || $rating == 2 || $rating == 3 || $rating ==4){
			$update = "UPDATE users SET hotness = hotness + $rating, totalhots = totalhots + 1 WHERE id = '$rid'";
		}
	}
        elseif (isset($_GET)){
                $uid=array_keys($_GET);
                $uid=intval(check_input($uid[0]));
        }
	
	$p=2;

 	if ($uid){$search = "SELECT * FROM users WHERE id = '".$uid."' LIMIT 1";}
        elseif (isset($_GET['u']) && intval($_GET['u'])>0){
                $uid = intval(check_input($_GET['u']));
		$search = "SELECT * FROM users WHERE id = '".$uid."' LIMIT 1";
        }
        else {
		//// random uid ??? rate just photos
		$search = "SELECT * FROM users WHERE thumbnail != '' ORDER BY RAND() LIMIT 1";
		$random = 1;			
        }
        $con = mysql_connect("localhost","root","bitnami");
        if (!$con){
                 die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("sexonmeals", $con);
	$output = mysql_query($search);
        $row =  mysql_fetch_assoc($output);
	if (($row['id'] == $id) && ($uid==0)){
		header("Location: ../rate");
                die();
	}
        include("header.php");
        if ($update){
                 mysql_query($update);
                 $update = "SELECT username, hotness, totalhots FROM users WHERE id ='$rid'";
                 $output = mysql_query($update);
                 $row2 =  mysql_fetch_assoc($output);
                 $output = round(intval($row2['hotness']) / intval($row2['totalhots']) * 2);
		 $output = $output /2 ;
                if ($row2){
                        echo "<div style='border:2px solid; background-color: #FEE; padding:5px 5px 0 5px; margin-top:20px;'><h4>You rated ".$row2['username']." as a ".$rating." star. Their average rating so far is ".$output." stars.</h4></div>";
		}
        }

	$uid = $row['id'];
	$_SESSION['rid'] = $uid;
        if ($uid==$id){
                echo "<h3>You cannot rate yourself!</h3><br /><br />";
                include("footer.php");
                die();
        }

function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

$message = "<h1>Leave a review of ".$row['username']."</h1>";
$message .= '<form action="rate" method="post" target="_blank">';
$message .= '<h4>How would you rate the date?</h4><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&nbsp;&nbsp;';
$message .= '<input name="rating1" value="1" type="radio" />&#9733;<font style="color:black;">&#10025;&#10025;&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating1" value="2" type="radio" />&#9733&#9733<font style="color:black;">&#10025;&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating1" value="3" type="radio" />&#9733&#9733&#9733<font style="color:black;">&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating1" value="4" type="radio" />&#9733&#9733&#9733&#9733</font><br />';
$message .= '<br />';
$message .= '<h4>How accurate were their photo likeness?</h4><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&nbsp;&nbsp;';
$message .= '<input name="rating2" value="1" type="radio" />&#9733;<font style="color:black;">&#10025;&#10025;&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating2" value="2" type="radio" />&#9733&#9733<font style="color:black;">&#10025;&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating2" value="3" type="radio" />&#9733&#9733&#9733<font style="color:black;">&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating2" value="4"  type="radio" />&#9733&#9733&#9733&#9733</font><br />';
$message .= '<br />';
$message .= '<h4>How would you rate this person overall?</h4><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&nbsp;&nbsp;';
$message .= '<input name="rating3" value="1" type="radio" />&#9733;<font style="color:black;">&#10025;&#10025;&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating3" value="2" type="radio" />&#9733&#9733<font style="color:black;">&#10025;&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating3" value="3" type="radio" />&#9733&#9733&#9733<font style="color:black;">&#10025;</font>&nbsp;&nbsp;';
$message .= '<input name="rating3" value="4" type="radio" />&#9733&#9733&#9733&#9733</font><br />';
$message .= '<br />';
$message .= '<h4>Leave a quick review:</h4>';
$message .= '<textarea cols="75" name="commentText" rows="5"></textarea><br />';
$message .= '<br />';
$message .= '<input type="submit" value="Submit your review" />&nbsp;</form>';

if (!$random){
if (isset($row['thumbnail'])){
        $ppp = "p".$row['thumbnail'];
        echo "<img src='../photos/".$uid."/".($row[$ppp])."' style='position:relative;top:80px;float:right;max-width:400px;max-height:500px;' />";
}
echo $message;
}
else {
if (isset($row['thumbnail'])){
	echo "<h1>Rate a Random Dish!</h1>";
        $ppp = "p".$row['thumbnail'];
        echo "<br /><br /><center><img src='../photos/".$uid."/".($row[$ppp])."' id='mainpic' name='mainpic' style='max-width:640px;max-height:640px;'  /><br />";
	for ($i=0;$i<10;$i++){
		$pp = "p".$i;
		if ($row[$pp] && $pp!=$ppp ){echo "<img src='../photos/".$uid."/".($row[$pp])."' onclick='var tpic = this.src; this.src =document.getElementById(\"mainpic\").src;document.getElementById(\"mainpic\").src=tpic;'  style='max-width:100px;max-height:100px;padding-right:5px;' />";}
	}
}
$message = '<h4>How attractive is this person?</h4>';
$message .= '<form method="POST" action="../rate">';
$message .= '<button value="1" name="rating" type="submit"><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&#9733</font></button>';
$message .= '<button value="2" name="rating" type="submit"><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&#9733&#9733</font></button>';
$message .= '<button value="3" name="rating" type="submit"><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&#9733&#9733&#9733</font></button>';
$message .= '<button value="4" name="rating" type="submit"><font style="color:yellow;font-size:200%;text-shadow: 0px 0px 5px #000;cursor:default;">&#9733&#9733&#9733&#9733</font></button><br />';
$message .= '</form><br /><a href="../v?'.$uid.'">Check out their profile here</a></center>';
echo $message;
}


echo "<br /><br />";
include('footer.php');
?>

