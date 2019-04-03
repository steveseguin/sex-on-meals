<?php 
	session_start();
	$id=0;
        $sort2 = "id DESC";
	$page1 = 0;
	$page2 = 30;
	if (isset($_GET['p'])){
		$page1 = 30*intval(check_input($_GET['p']));
		$page2 = $page1+30;
	}
	if (isset($_GET['list'])){
		$e1="&list";
		$b2="0";
	}
	else {$b1="0";}
        if (isset($_SESSION['id']) && isset($_SESSION['lon']) && isset($_SESSION['lat'])){
                $id = $_SESSION['id'];
                $lon = $_SESSION['lon'];
                $lat = $_SESSION['lat'];

	        if (isset($_GET['donations'])){
			$a2=1;
			$e2="&donations";
	                $sort2='verified DESC, id DESC';
	                $sql = "SELECT * FROM users WHERE latitude BETWEEN ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
	        }
        	elseif (isset($_GET['ratings'])){
			$a3=1;
			$e2="&ratings";
        	        $sort2='ratings DESC, id DESC';
                        $sql = "SELECT * FROM users WHERE latitude BETWEEN  ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
        	}
        	elseif (isset($_GET['distance']) ){
			$a4=1;
			$e2="&distance";
        	        $sql = "SELECT *, (6371 * acos(cos($lat)*cos(latitude)*cos(longitude - $lon) + sin($lat)*sin(latitude))) AS distance FROM users WHERE latitude BETWEEN  ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) HAVING distance < 100 ORDER BY distance ASC, id DESC LIMIT ".$page1.", ".$page2;
        	}
        	else {
			$a1=1;
        	        $sql = "SELECT * FROM users WHERE latitude BETWEEN ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) ORDER BY id DESC LIMIT ".$page1.", ".$page2;
        	}
	}
	elseif (isset($_SESSION['id'])){
		$id = $_SESSION['id'];
		$output = mysql_query("SELECT latitude, longitude FROM users WHERE id = '$id' LIMIT 1");
		if ($row = mysql_fetch_assoc($output)){
			$lon = $row['longitude'];
                	$lat = $row['latitude'];
			$_SESSION['lon'] = $lon;
			$_SESSION['lat'] = $lat;
			
        		if (isset($_GET['donations'])){
				$a2=1;
				$e2="&donations";
        		        $sort2='verified DESC, id DESC';
                        	$sql = "SELECT * FROM users WHERE latitude BETWEEN  ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
			}
        		elseif (isset($_GET['ratings'])){
				$a3=1;
				$e2="&ratings";
        		        $sort2='ratings DESC, id DESC';
	                        $sql = "SELECT * FROM users WHERE latitude BETWEEN  ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
        		}
        		elseif (isset($_GET['distance']) ){
				$a4=1;
				$e2="&distance";
        			$sql = "SELECT *, (6371 * acos(cos($lat)*cos(latitude)*cos(longitude - $lon) + sin($lat)*sin(latitude))) AS distance FROM users WHERE latitude BETWEEN  ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) HAVING distance < 100 ORDER BY distance ASC, id DESC LIMIT ".$page1.", ".$page2;
			}
        		else {
				$a1=1;
	                        $sql = "SELECT * FROM users WHERE latitude BETWEEN  ($lat-1/57) AND ($lat+1/57) AND longitude BETWEEN $lon-(1/57)*(1/COS($lat)) AND $lon+(1/57)*(1/COS($lat)) ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
	        	}

		}
		else {
        		if (isset($_GET['donations'])){
				$a2=1;
				$e2="&donations";
        		        $sort2='verified DESC, id DESC';
        		}
        		elseif (isset($_GET['ratings'])){
				$a3=1;
				$e2="&ratings";
        		        $sort2='ratings DESC, id DESC';
        		}
			elseif (isset($_GET['distance'])){
				$a4=1;
				$e2="&distance";
			}
			else {
				$a1=1;
			}
			$sql = "SELECT * FROM users ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
		}
	}
	else {
		$a1=1;
		$sql = "SELECT * FROM users WHERE thumbnail IS NOT NULL ORDER BY ".$sort2." LIMIT ".$page1.", ".$page2;
	}


	$p=1;
	include("header.php"); 

function findAge($year, $month, $day){
	$date1 = $year."-".$month."-".$day;
	$date2 = strval(date('y-m-d'));
	$diff = abs(strtotime($date2) - strtotime($date1));
	$years = floor($diff / (365*60*60*24));
	return $years;
}

function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

	if (!$id) { echo "<div id='whatis' name='whatis'><img src='../images/mainbanner.png' style='border-left: 6px dotted #E6A1F3;border-bottom: 7px dotted #D78BFF;' /></div>"; }

	$con = mysql_connect("localhost","root","bitnami");

	if (!$con){
				die('Error: Could not connect to database.');
			}
	else {
				mysql_select_db("sexonmeals", $con);
				
				//$output = mysql_query("SELECT * FROM users ORDER BY ".$sort." DESC LIMIT 30");
				$output = mysql_query($sql);
				if (!$output) {
					$continue = 0;
					die('DATABASE ERROR');
				}
				else {
					if ($id>0){
				echo "<h2>Menu Items</h2><div id='navleft'><div class='tab".$a1."' ><a href='../?".$e1."'><h3>Newest</h3></a></div><div class='tab".$a2."'><a href='../?donations".$e1."'><h3>Donations</h3></a></div><div class='tab".$a3."'><a href='../?ratings".$e1."'><h3>Rating</h3></a></div><div class='tab".$a4."'><a href='../?distance".$e1."'><h3>Distance</h3></a></div></div><div id='navright'><div class='tab".$b1."'><a href='../?".$e2."'><h3>Gallery View</h3></a></div><div class='tab".$b2."'><a href='../?list".$e2."'><h3>List View</h3></a></div><div class='tab".$b2."'><a href='../?advanced".$e2."'><h3>Advanced</h3></a></div></div><br /><br />";
					}
					else {
						echo "<br /><h2>Newest Menu Items</h2><div style='display:table-cell;'>";
					}
					while ($result = mysql_fetch_assoc($output)){
					    if ($id != $result['id']){
						if (!isset($_GET['list'])){
						echo "<div class='minibox'>";
						echo "<a href='../v?".$result['id']."' style='border:0;color:black;'><b>".$result['username']."</b></a><br />";
						if ($result['gender']==1){echo "Male | ";} elseif ($result['gender']==2){echo "Female | ";}
						echo "Age: ".findAge($result['birthyear'],$result['birthmonth'],$result['birthday'])."<br />";
						
						if ($result['verified']>1){echo "Donated: <b>$".$result['verified']."</b> | ";}
                                        	elseif ($result['verified']){
	                                        	echo "Donated: $1 | ";
                                        	}
						else {
							echo "Unverified | ";
						}
						echo "Ratings: ".$result['ratings'];
						if (isset($_GET['distance'])) { echo "<br />Distance: ".round($result['distance'])."km"; }
						echo "<br /><a href='../v?".$result['id']."'><img src='";
						if (strlen($result['thumbnail'])){
							echo 'photos/'.$result['p'.$result['thumbnail']];
						}
						else {
							echo "../images/nothumb.jpg";
						}
						echo "' /></a></div>";
					    	}

						/////////////////////// LIST VIEW ///////////////////////////

						else {

						echo "<div class='longbox'>";
						echo "<a href='../v?".$result['id']."'><img style='vertical-align:text-top;' src='";
                                                if (strlen($result['thumbnail'])){
                                                        echo 'photos/'.$result['id']."/t_".$result['p'.$result['thumbnail']];
                                                }
                                                else {
                                                        echo "../images/nothumb.jpg";
                                                }
						echo "' /></a>";
                                                echo "<div class='profInfo'><a href='../v?".$result['id']."' style='border:0;color:black;'><h3>".$result['username']."</h3></a> ";
                                                if ($result['gender']==1){echo "Male | ";} elseif ($result['gender']==2){echo "Female | ";}
                                                echo "Age: ".findAge($result['birthyear'],$result['birthmonth'],$result['birthday'])." | ";

                                                if ($result['verified']>1){echo "Donated: <b>$".$result['verified']."</b> | ";}
                                                elseif ($result['verified']){
                                                        echo "Donated: $1 | ";
                                                }
                                                else {
                                                        echo "Unverified | ";
                                                }
                                                echo "Rating: ".$result['ratings'];
						
						if (isset($result['distance'])) { echo " | Distance: ".round($result['distance'])."km"; }
                                                elseif ($result['location']) { echo " | ".$result['location']; }
						echo "<br /><br /><i><b>".$result['short'];
                                                echo "</b></i></div>";
						echo "</div>";
						}
					    }
					}
					echo "<br />";
				}
	}
echo "<br /></div><br />";
echo "<a href='../?p=".(intval($_GET['p'])+1).$e1.$e2."'><button style='float:right;position:relative;left:-15px;'>Next</button></a>";
if (isset($_GET['p']) && intval($_GET['p'])>0){
	echo "<a href='../?p=".(intval($_GET['p'])-1).$e1.$e2."'><button style='float:left;position:relative;right:-30px;'>Back</button></a>";
}

?>
<br /><br /><br /><br />

<?php include("footer.php"); ?>
