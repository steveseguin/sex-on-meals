<?php 
	session_start();
	if (isset($_SESSION['id'])){
		$id = $_SESSION['id'];
	}
	else {
		header("Location: login");
		die();
	}

	if ((isset($_GET['money'])) && (isset($_GET['where']))  && (isset($_GET['sex'])) && (isset($_GET['gender'])) && (isset($_GET['cost']))){
		$money = check_input($_GET['money']);
		$where = check_input($_GET['where']);
		$sex   = check_input($_GET['sex']);
		$gender= check_input($_GET['gender']);	
		$cost  = check_input($_GET['cost']);
		if (isset($_GET['type'])){
                        $type=check_input($_GET['type']);
                }
                if (isset($_GET['address'])){
                        $address=check_input($_GET['address']);
                }
                if (isset($_GET['date'])){
                        $date=check_input($_GET['date']);
                }
                if ((isset($_GET['longitude'])) && (isset($_GET['latitude']))){
                        $longitude=check_input($_GET['longitude']);
			$latitude=check_input($_GET['latitude']);
                }
                if (isset($_GET['title'])){
                        $title=check_input($_GET['title']);
                }
                if (isset($_GET['description'])){
                        $description=check_input($_GET['description']);
                }
		if (isset($_GET['otherdate'])){
			$other=check_input($_GET['otherdate']);
		}
 		$con = mysql_connect("localhost","root","bitnami");
                if (!$con){
                        die('Could not connect: ' . mysql_error());
                }
                mysql_select_db("sexonmeals", $con);
                $result = mysql_query("INSERT INTO tables (title,description,host,date,address,longitude,latitude,type,sex,gender,cost,money,other) VALUES ('$title','$description','$id','$date','$where','$longitude','$latitude','$type','$sex','$gender','$cost','$money','$other')");	
		if ($result){die("1");} else {die("TABLE SQL CREATION FAIL");}
	}
	elseif (isset($_GET['money']) || isset($_GET['where'])  || isset($_GET['sex']) || isset($_GET['gender']) || isset($_GET['cost'])){
		die("Not all fields entered. Please try again");
	}

        include("header.php");

?>
<script>
function getquerystring(formname) {

    var form = formname;

	var qstr = "";



    function GetElemValue(name, value) {

        qstr += (qstr.length > 0 ? "&" : "")

            + escape(name).replace(/\+/g, "%2B") + "="

            + escape(value ? value : "").replace(/\+/g, "%2B");

			//+ escape(value ? value : "").replace(/\n/g, "%0D");

    }

	

	var elemArray = form.elements;

    for (var i = 0; i < elemArray.length; i++) {

        var element = elemArray[i];

        var elemType = element.type.toUpperCase();

        var elemName = element.name;

        if (elemName) {

            if (elemType == "TEXT"

                    || elemType == "TEXTAREA"

                    || elemType == "PASSWORD"

					|| elemType == "BUTTON"

					|| elemType == "RESET"

					|| elemType == "SUBMIT"

					|| elemType == "FILE"

					|| elemType == "IMAGE"

                    || elemType == "HIDDEN")

                GetElemValue(elemName, element.value);

            else if (elemType == "CHECKBOX" && element.checked)

                GetElemValue(elemName, 

                    element.value ? element.value : "On");

            else if (elemType == "RADIO" && element.checked)

                GetElemValue(elemName, element.value);

            else if (elemType == "NUMBER" && element.value)

                GetElemValue(elemName, element.value);

            else if (elemType.indexOf("SELECT") != -1)

                for (var j = 0; j < element.options.length; j++) {

                    var option = element.options[j];

                    if (option.selected)

                        GetElemValue(elemName,

                            option.value ? option.value : option.text);

                }

        }

    }

    return qstr;

}
    function ajaxTable(tForm){
	
	var callString = "../submit?"+getquerystring(tForm); 
	var xhReq = new XMLHttpRequest();
        xhReq.open("GET", callString, false);
        xhReq.send(null);
        if (xhReq.responseText==1){return true;}
	return xhReq.responseText;
	}
    function ajaxCall(callString){
        var xhReq = new XMLHttpRequest();
        xhReq.open("GET", callString, false);
        xhReq.send(null);
        var serverResponse = xhReq.responseText;
        document.getElementById("predictinput").innerHTML = serverResponse;
    	if ( document.getElementById("predictinput").innerHTML.length > 0 ) {
		document.getElementById("predictinput").style.display="block";
	} 
	else {
		document.getElementById("predictinput").style.display="none";
	}
    }
</script>

<h1>Set your Table</h1>
<div id='allDone' style="display:none;"><h3>Table Create.</h3></div>
<form action='../submit' method='POST' id='createtable' name='createtable' onsubmit='document.getElementById("allDone").style.display="block";if (ajaxTable(this)=="1"){this.style.display="none";document.getElementById("allDone").innerHTML="<h3>Table Created.</h3>";}else {document.getElementById("allDone").innerHTML="<h4><i>One of the fields is not filled out correctly.</i></h4>";} return false;' >
<h4>What type of Date do you Propose?</h4>
<select id='type' name='type'>
  <option>Texting Date</option>
  <option>Phone Call Date</option>
  <option>Video Chat Date</option>
  <option>Coffee Date</option>
  <option>Cocktails/Drinks</option>
  <option>Dinner Date</option>
  <option>Movie Date</option>
  <option>Dancing</option>
  <option>Skip the foreplay</option>
  <option>Other</option>
</select>
 If other, please specify: <input type='text' name='otherdate'>
<br /><br /><br />
<h4>Please enter a short title for your table</h4><input type='text' size='50' name='title'>
<br /><br /><br />
<h4>Please describe your date offer</h4><input type='text' size='100' name='description'>
<br /><br /><br />
<h4>What gender is this date open to?</h4>
<input type="radio" name="gender" value="female">Females
<input type="radio" name="gender" value="male">Males
<input type="radio" name="gender" value="both">Both
<br /><br /><br />
<h4>Who is paying for the date?</h4>
<input type="radio" name="money" value="first">I'll play for the date
<input type="radio" name="money" value="second">We split the bill
<input type="radio" name="money" value="third">They pay for the date
<input type="radio" name="money" value="strike">Depends on if they put out
<br /><br /><br />
<h4>How much do you expect this date to cost?</h4>
$$$ <input type="number" name="cost" value="">
<br /><br /><br />
<h4>What do you expect from this encounter?</h4>
<input type="radio" name="sex" value="first">First Base
<input type="radio" name="sex" value="second">Second Base
<input type="radio" name="sex" value="third">Third Base
<input type="radio" name="sex" value="strike">Nothing.
<br /><br /><i>Remember, do not force your expectations on others. Accepting your expections on a date does NOT imply commitment.</i>
<br /><br /><br />
<h4>Where will this date be taking place?</h4>
<div id='container' style='position:relative;'>
<input type='text' name='where' id='where' style='height:30px;width:386px;position:absolute;top:0;left:0;z-index:9999;padding:0;margin:0;'
 onblur='var predict = encodeURI(this.value); ajaxCall("../search?q="+predict+"<?php if (isset($_SESSION['lat']) && isset($_SESSION['lon'])){ echo "&lat=".$lat."&lon=".$lon; }?>");' />
<br />
<select id='predictinput' style='display:none;position:absolute;left:0;top:0;margin:0;padding:0px;width:410px;height:30px;' onchange="document.getElementById('where').value=this.value;"></select>
</div><br /><br /><br />
<input type='submit' value='Create Table' style='float:right;width:300px;font-size:300%;position:relative;top:-50px;' />
</form>
<br /><br />

<?php 
function check_input($data){  ///  USED TO CHECK ALL INCOMING USER INPUT -- anti-hacking stuff
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = htmlentities($data);
    $data = mysql_real_escape_string($data);
    return $data;
}

    include("footer.php"); 
?>
