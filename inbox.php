<?php 

        session_start();
        $id=0;
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
	elseif ((isset($_GET['mid'])) || (isset($_GET['mhid']))){
		die("0");
		//  must be logged in; otherwise return a failed AJAX reply
	}
	else {
		header("Location: ../login");
                die();
	}
        $p=4;
	if (isset($_GET['mhid'])){
		$con = mysql_connect("localhost","root","bitnami");
                if (!$con){
                        die('0');
                }
		mysql_select_db("sexonmeals", $con);
                $result = mysql_query("SELECT * FROM messages WHERE rid = '$id' OR sid = '$id' ORDER BY id DESC LIMIT 30");
		 	$c = 1;
                        echo "<table style='width:900px;table-layout: fixed;'>";
                                echo "<colgroup>
                                        <col width='140px' />
                                        <col width='130px' />
					<col width='130px' />
                                        <col width='auto' />
                                        </colgroup>";
                                echo "<tr class='tTitle'>";
                                        echo "<td>Date Received</td>";
                                        echo "<td>Sender</td>";
                                        echo "<td>Recipient</td>";
                                        echo "<td>Subject</td>";
                                echo "</tr>";
                        while ($row = mysql_fetch_assoc($result)){
                            if (((!$row['deleted']) && ($row['rid']==$id)) || ($row['sid']==$id)){
                                $c++;
                                echo "<tr id='row$c' class='";
                                if ($c%2==1){echo "odd";} else{echo "even";}
                                //if (!$row['dateread']){echo " newMsg";}
                                        echo "'>";
                                        echo "<td onclick='slideToggle(document.getElementById(\"mhb$c\"));' class='date'>".$row['datesent']."</td>";
                                        echo "<td onclick='slideToggle(document.getElementById(\"mhb$c\"));' class='sender'><a href='../v?".$row['sid']."' onclick='event.stopPropagation();return;'>".$row['sid']."</a></td>";
                                        echo "<td onclick='slideToggle(document.getElementById(\"mhb$c\"));' class='sender'><a href='../v?".$row['rid']."' onclick='event.stopPropagation();return;'>".$row['rid']."</a></td>";
                                        echo "<td onclick='slideToggle(document.getElementById(\"mhb$c\"));' class='subject'>".$row['subject']."</td>";
                                echo "</tr>";
                                echo "<tr><td id='mhb$c' class='mbox' style='display:none;width:900px;border:0;padding:0;height:0px;overflow:hidden;'><p>".$row['message']."</p>
                                        <a onclick='slideToggle(document.getElementById(\"mhb$c\"))'>^ Close</a>
                                        </td></tr>";
                            }
                        }
                        echo "</table>";
                        if ($c==1){
                                echo "<h2>No Chat History</h2>";
                                echo "<br /><br /><br />";
                        }
			die();

	}
	elseif (isset($_GET['mid'])){
		$mid = check_input($_GET['mid']);
		$con = mysql_connect("localhost","root","bitnami");
		if (!$con){
                        die('0');
                }
		$Tod = strval(date('D-M-Y, G:i:s'));
		mysql_select_db("sexonmeals", $con);
                $result = mysql_query("UPDATE messages SET deleted = '$Tod' WHERE id = '$mid' AND rid = '$id' LIMIT 1");
		if ($result){die('1');}
		die("0");
	}
	include("header.php");
	?>
	<script>
		function ajaxCall(callString){
        		var xhReq = new XMLHttpRequest();
        		xhReq.open("GET", callString, false);
        		xhReq.send(null);
        		var serverResponse = xhReq.responseText;
			if ( serverResponse == "0" ) {alert ('server error');}
			else if (serverResponse == "1"){}
			else { return serverResponse; }
		}

		function slideToggle(sElement){
			if (parseInt(sElement.style.height,10) == 0){
				sElement.style.display="block";
				slideOpen(sElement);
			}
			else {
				slideClosed(sElement);
			}
		}
		function slideOpen(sElement){
			if (parseInt(sElement.style.height,10) < sElement.scrollHeight){
				sElement.style.height = (parseInt(sElement.style.height, 10) + 15) + "px";
				setTimeout(function(){slideOpen(sElement);},10);		
			}
		}
		function slideClosed(sElement){
                        if (parseInt(sElement.style.height,10) > 5){
                                sElement.style.height = (parseInt(sElement.style.height, 10) - 15) + "px";
                                setTimeout(function(){slideClosed(sElement);},10);
                        }
			else {sElement.style.height = 0; sElement.style.display='none'; }
                }
	</script>
	<?php	

	echo "<div class='tab1' id='trec' onclick=\"document.getElementById('tsent').className='tab';
							document.getElementById('hbox').style.display='none';
							document.getElementById('ibox').style.display='block';	
							document.getElementById('sbox').style.display='none';
							this.className='tab1';\">
						<h3>Inbox</h3></div><div class='tab' 
							onclick=\"document.getElementById('sbox').style.display='block';
						document.getElementById('ibox').style.display='none';
						document.getElementById('hbox').style.display='none';
						this.className='tab1';document.getElementById('trec').className='tab';\" 	
						id='tsent'><h3>Sent</h3></div><div class='tab'><h3>Preferences</h3></div>";
		echo "<div id='hbox'></div>";
                $con = mysql_connect("localhost","root","bitnami");
                if (!$con){
                        die('Could not connect: ' . mysql_error());
                }
                else {
                        mysql_select_db("sexonmeals", $con);
                        $output = mysql_query("SELECT * FROM messages WHERE rid = '$id' ORDER BY id DESC LIMIT 30");
                        if (!$output) {
                                die('DATABASE ERROR');
                        }
                       
			$c = 1;
			echo "<div id='ibox'><table style='width:900px;table-layout: fixed;'>";
                                echo "<colgroup>
  					<col width='140px' />
  					<col width='130px' />
  					<col width='auto' />
					<col width='52px' />
                                        <col width='47px' />
                                        <col width='43px' />
 					</colgroup>";
				echo "<tr class='tTitle'>";
                                        echo "<td>Date Received</td>";
                                        echo "<td>Sender</td>";
                                        echo "<td>Subject</td>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                        echo "<td></td>";
                                echo "</tr>";
			$Tod = strval(date('D-M-Y, G:i:s'));
			while ($row = mysql_fetch_assoc($output)){
			    if (!$row['deleted']){
				mysql_query("UPDATE messages SET dateread = '$Tod' WHERE id = '".$row['id']."' AND dateread = '' LIMIT 1");
				$c++;
				echo "<tr id='row$c' onclick='this.style.fontWeight=\"normal\"' class='";
				if ($c%2==1){echo "odd";} else{echo "even";}
				if (!$row['dateread']){echo " newMsg";}
					echo "'>";
					echo "<td onclick='slideToggle(document.getElementById(\"mb$c\"));' class='date'>".$row['datesent']."</td>";
					echo "<td onclick='slideToggle(document.getElementById(\"mb$c\"));' class='sender'><a href='../v?".$row['sid']."' onclick='event.stopPropagation();return;'>".$row['sid']."</a></td>";
					echo "<td onclick='slideToggle(document.getElementById(\"mb$c\"));' class='subject'>".$row['subject']."</td>";
					echo "<td onclick='document.getElementById(\"hbox\").innerHTML = ajaxCall(\"../inbox?mhid=".$row["sid"]."\");
						document.getElementById(\"trec\").className=\"tab\";
						document.getElementById(\"ibox\").style.display=\"none\";
						document.getElementById(\"hbox\").style.display=\"block\";' 
						class='reply'>History</td>";
					echo "<td onclick='if (this.innerHTML==\"Delete\"){this.innerHTML=\"Confirm\";
								this.setAttribute(\"class\",\"confirm\");}
							else {document.getElementById(\"row$c\").style.display=\"none\";
								document.getElementById(\"mb$c\").style.display=\"none\";
								ajaxCall(\"../inbox?mid=".$row['id']."\")}' 
							class='del'>Delete</td>";
					echo "<td onclick='document.getElementById(\"minireply$c\").style.display=\"none\";slideToggle(document.getElementById(\"rb$c\"));' class='reply'>Reply</td>";
				echo "</tr>";
				echo "<tr><td id='mb$c' class='mbox' style='display:none;width:900px;border:0;padding:0;height:0px;overflow:hidden;'><p>".$row['message']."</p>
					<a onclick='slideToggle(document.getElementById(\"mb$c\"))'>^ Close</a> | 
					<a id='minireply$c' onclick='document.getElementById(\"rb$c\").style.display=\"block\";slideOpen(document.getElementById(\"rb$c\"));this.style.display=\"none\";'> Reply</a>
					</td></tr>";
			    	echo "<tr><td id='rb$c' class='rbox' style='display:none;width:900px;border:0;padding:0;height:0px;overflow:hidden;'>";
				echo "	<form action='message?u=".$row['sid']."' method='POST'>
        					<input type='hidden' id='mTitle' name='mTitle' style='width:500px;' value='Re: ".$row['subject']."'/><br />
        					<h4>Quick Reply to ".$row['sid'].":</h4>
        					<input type='textbox' id='mBody' name='mBody' style='height:500px;width:700px;' /><br /><br />
        					<input type='submit' value='Send!' style='width:200px;height:40px;' />
						<input type='button' value='Cancel'  style='width:70px;height:40px;' onclick='slideClosed(document.getElementById(\"rb$c\"));document.getElementById(\"minireply$c\").style.display=\"inline\";' />
        					<br /><br />
					</form>";
				echo "</td></tr>";
				}
			}
			echo "</table>";
                        if ($c==1){
                                echo "<h2>Inbox empty</h2>";
                		echo "<br /><br /><br />"; 
			}
			echo "</div>";
                        $output = mysql_query("SELECT * FROM messages WHERE sid = '$id' ORDER BY id DESC LIMIT 30");
                        if (!$output) {
                                die('DATABASE ERROR');
                        }

                        $c = 1;
                        echo "<div id='sbox' style='display:none'><table style='width:900px;table-layout: fixed;'>";
                                echo "<colgroup>
                                        <col width='130px' />
                                        <col width='130px' />
                                        <col width='auto' />
                                        <col width='80px' />
                                        </colgroup>";
				echo "<tr>";
                                        echo "<td>Date Sent</td>";
                                        echo "<td>Recipient</td>";
                                        echo "<td>Subject</td>";
                                echo "</tr>";
                        while ($row = mysql_fetch_assoc($output)){
                                $c++;
                                echo "<tr onclick='slideToggle(document.getElementById(\"sb$c\"));' class='";
                                if ($c%2==1){echo "odd'>";} else{echo "even'>";}
                                        echo "<td class='date'>".$row['datesent']."</td>";
                                        echo "<td class='sender'><a href='../v?".$row['rid']."' onclick='event.stopPropagation();return;'>".$row['rid']."</a></td>";
                                        echo "<td class='subject'>".$row['subject']."</td>";
                                echo "</tr>";
                        	echo "<tr><td id='sb$c' class='mbox' style='display:none;width:900px;border:0;padding:0;height:0px;overflow:hidden;'><p>".$row['message']."</p></td></tr>";
			}
                        echo "</table></div>";



		}


function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}


 include("footer.php") ?>
