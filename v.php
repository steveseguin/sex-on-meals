<?php
        session_start();
        $id =0;
        $uid=0;
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
	
	if (isset($_GET)){
		$uid=array_keys($_GET);
		$uid=intval(check_input($uid[0]));
	}
	if ($uid){}
        elseif (isset($_GET['u']) && intval($_GET['u'])>0){
                $uid = intval(check_input($_GET['u']));
        }
        elseif ($id > 0){
                $uid = $id;
        }
 	else {
	        header("Location: login");
                die();
	}
	

	$p=5;
	include("header.php");

        $con = mysql_connect("localhost","root","bitnami");
        if (!$con){
                 die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("sexonmeals", $con);
        $output = mysql_query("SELECT * FROM users WHERE id = '$uid' LIMIT 1");
        $row =  mysql_fetch_assoc($output);
				
function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

function findAge($year, $month, $day){
        $date1 = $year."-".$month."-".$day;
        $date2 = strval(date('y-m-d'));
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        return $years;
}

    echo "<h1>About ".$row['username']."</h1>";
    echo "<div id='infobox' style='min-height:215px'><div style='float:right;width:200px;height:276px;vertical-align:text-top;display:inline;padding-left:20px;position:relative;top:-30px;'><table>";
    $age = findAge($row['birthyear'],$row['birthmonth'],$row['birthday']);
    echo "<h4>";
    if ($age>1){echo "<tr><td>Age:</td><td>".$age."</td></tr>";}
    if ($row['gender']==1){echo  "<tr><td>Sex:</td><td>Male</td></tr>";}elseif ($row['gender']==2){echo "<tr><td>Sex:</td><td>Female</td></tr>";}
    if ($row['verified']>0){echo "<tr><td>Account:</td><td>Verified</td></tr>";} else{echo "<tr><td>Account:</td><td>Unverified</td></tr>";}
    if ($row['location']){echo "<tr><td>Location:</td><td>".$row['location']."</td></tr>";} else{echo "<tr><td>Location:</td><td>Unknown</td></tr>";}
    echo "<tr><td>Donated:</td><td>$ ".$row['verified']."</td></tr>";
    echo "<tr><td>Reviews</td><td>".$row['ratings']."</td></tr>";
    echo "<tr><td>Avg. Rating:</td><td>".$row['ratings']."</td></tr>";
    echo "<tr><td><a href='../message?".$uid."'><button>Message</button></a></td><td><a href='../rate?".$uid."'><button>Review</button></a></td></tr>";
    echo "</h4></table></div>";
    echo "<h2>Brief</h2><div id='shortd'>".$row['short']."</div>";
    echo "<br /><h2>Details</h2><div id='longd'>".$row['longd']."</div>";
    echo "</div id='infobox'><br /><h2>Photo Gallery</h2>";
    $x=0;$y=0;
    while ($x<9){
        if ($row['p'.$x]){
		$y++;
                echo "<div class='addedFile' id='p".$x."'>
		<img src='photos/".$uid."/".$row['p'.$x]."' id='img_p".$x."' class='picZoom".($y%3)."' />
		</div>";
        }
	$x++;
    }
    if (!$y){echo "This user has no photos available.";}
	
?>
<br />
<br /><br />
<?php include("footer.php"); ?>
