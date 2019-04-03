<?php 
	session_start();
	$id =0 ;
	if (isset($_SESSION['id'])){
		$id = $_SESSION['id'];
	}
	elseif (isset($_GET['d']) || isset($_GET['p']) ){
		echo "You are no longer signed in.";
	}
	else {
		header("Location: login");
		die();
	}
/////////////////////  DELETE PHOTO	
	if ((isset($_GET['d'])) && (strlen(check_input($_GET['d']))==1)){
		$imid = intval(check_input($_GET['d']));
		$con = mysql_connect("localhost","root","bitnami");
		if (!$con){
                	 die('Database error');
        	}
		mysql_select_db("sexonmeals", $con);
                if (isset($_SESSION['thumb']) && $_SESSION['thumb']==$imid){
			 mysql_query("UPDATE users SET p".$imid." = '', thumbnail = '' WHERE id = '$id'");
		}
		else {
			mysql_query("UPDATE users SET p".$imid." = '' WHERE id = '$id'");
		}
		die('Image Deleted');
	} //////////////////// SET PHOTO AS PRIMARY
	elseif ((isset($_GET['p'])) && (strlen(check_input($_GET['p']))==1)){
                $imid = intval(check_input($_GET['p']));
                $con = mysql_connect("localhost","root","bitnami");
                if (!$con){
                         die('Database error');
                }
                mysql_select_db("sexonmeals", $con);
                mysql_query("UPDATE users SET thumbnail = '$imid' WHERE id = '$id'");
		$output = mysql_query("SELECT p".$imid." FROM users WHERE id = '$id' LIMIT 1");
		$row =  mysql_fetch_row($output);
        	
		die('Primary Set');
	}

	$p=5;
	include("header.php");

        $con = mysql_connect("localhost","root","bitnami");
        if (!$con){
                 die('Could not connect: ' . mysql_error());
        }
        mysql_select_db("sexonmeals", $con);
	if (isset($_POST['shortf']) && isset($_POST['longf']) && strlen(check_input($_POST['shortf']))>0 && strlen(check_input($_POST['longf']))>0){
		$shortd = check_input($_POST['shortf']);
		$longd = check_input($_POST['longf']);
		mysql_query("UPDATE users SET longd='$longd', short='$shortd' WHERE id='$id'");	
	}
	elseif (isset($_POST['shortf'])  && strlen(check_input($_POST['shortf']))>0){
		$shortd = check_input($_POST['shortf']);
                mysql_query("UPDATE users SET short='$shortd' WHERE id='$id'");
	}
        elseif (isset($_POST['longf'])   && strlen(check_input($_POST['longf']))>0){
		$longd = check_input($_POST['longf']);
		echo $longd;
                mysql_query("UPDATE users SET longd='$longd' WHERE id='$id'");
        }

	if (isset($_GET['location'])){
       		$cmd = "php locate.php ".$id." > /dev/null &";
       		@exec($cmd);
	}
	////  ADD A WAY TO CHANGE LOCATION HERE

        $output = mysql_query("SELECT * FROM users WHERE id = '$id' LIMIT 1");
        $row =  mysql_fetch_assoc($output);
	$_SESSION['thumb']=$row['thumbnail'];



				
function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

?>

    <script>
    function ajaxCall(callString){
        var xhReq = new XMLHttpRequest();
        xhReq.open("GET", callString, false);
        xhReq.send(null);
        var serverResponse = xhReq.responseText;
       	//alert(serverResponse); // Shows "15"
    }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/plupload.full.js"></script>
    <script src="js/jquery-progressbar.min.js"></script>
    <script src="js/upload.js"></script>

<?php 
if (isset($_GET['new'])){
	echo "<h3>Create your public profile</h3>";
}
else {
	echo "<h3>Update your public profile</h3>";
}
?>

<h1>About yourself</h1>
<textarea id="title" rows="2" cols="50" 
	onFocus="this.style.color='black';if(this.value == 'Short Description') {
		this.value = '';}" 
	onBlur="if (this.value == '' || this.value == 'Short Description') 
		{this.value = 'Short Description';
		this.style.color='#888888';}
                else {
			this.style.color='black';
			document.getElementById('shortf').value=this.value;
		}"><?php if ($row['short']){echo $row['short'];} else {echo 'Short Description';} ?></textarea>

<textarea maxlength="1500" cols="73" rows="15" id="long_desc" 
	onFocus="this.style.color='black';if(this.value == 'Long Description') 
		{this.value = '';}" 
	onBlur="if (this.value == '' || this.value == 'Long Description') 
		{this.value = 'Long Description';
		this.style.color='#888888';}
		else {
			this.style.color='black';
			document.getElementById('longf').value=this.value;	
		}"><?php if ($row['longd']){echo $row['longd'];} else {echo 'Long Description';} ?></textarea>

<form action='edit' method='POST'>
        <input type='hidden' value='' id='shortf' name='shortf'/>
        <input type='hidden' value='' id='longf' name='longf'/>
        <input type='submit' value='Submit!'/>
</form><br /><br />
<a href='../v?<?php echo $id ?>'><button>View Your Public Profile</button></a>
<h1>Upload Photos</h1>
<br />
<div class="upload-form" id="uploader">
    <div>
        <button id="pickfiles" href="#">Select</button>
        <button id="uploadfiles" href="#">Upload</button>
    </div>
    <div id="progressbar"></div>
    <div id="filelist" class="cb">
<br />
<?php
    $x=0;
    while ($x<9){
        if ($row['p'.$x]){
		$delete = '../edit?d='.$x;
                $primary = '../edit?p='.$x;
                echo "<div class='addedFile' id='p".$x."'><img src='photos/".$id."/".$row['p'.$x]."' id='img_p".$x."' class='profPics' />
		<a href='#' id='p".$x."' class='removeFile' onclick='ajaxCall(\"".$delete."\");$(\"#p".$x."\").remove();return false;' >Delete</a> |"; 
		if ($row['thumbnail']==$x){
		    echo " <a href='#' id='p".$x."' class='setPrimary' onclick='ajaxCall(\"".$primary."\");$(\".setPrimary\").html(\"Set as Primary\");$(this).html(\"<div>Primary Image</div>\");return false;' ><div>Primary Image</div></a></div>";
                }
                else {
		    echo " <a href='#' id='p".$x."' class='setPrimary' onclick='ajaxCall(\"".$primary."\");$(\".setPrimary\").html(\"Set as Primary\");$(this).html(\"<div>Primary Image</div>\");return false;' >Set as Primary</a></div>";
                }

        }
	$x++;
    }
?>




    </div>
</div><br />
<?php include("footer.php"); ?>
