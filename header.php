<!DOCTYPE html>
<html>
<head>
  <title>Sex on Meals</title>
  <meta name="description" content="Making the first date the new third date since 2012!" >
  <meta name="keywords" content="dating, match, partner, sex, dinner, Sex On Meals" >
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" >
  <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro" rel='stylesheet' type='text/css' >
  <link href='http://fonts.googleapis.com/css?family=Raleway+Dots' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="style/<?php  if (isset($_SESSION['sex']) && $_SESSION['sex']==1){ echo "dark";} else { echo "style";} ?>.css" >
</head>
<body>
<div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <h1><a href="../">Sex on <span class="logo_colour">Meals</span></a></h1>
         	<?php 
			$x = rand(1,2);
			if ($x ==1){
	  echo "<h2>Take out or Dine in, never have sex on an empty stomach again!</h2>";
			}
			elseif ($x ==2){
          echo "<h2>Making the first date the new third date since 2012!</h2>";
                        }
		?>
        </div><br />
	<div id="order" onmouseout="this.style.opacity=0.9;this.filters.alpha.opacity=90" onmouseover="this.style.opacity=1;this.filters.alpha.opacity=100;" >
		<a href="<?php if ($id>0){ echo "submit"; } else {echo "register"; } ?>" style="color:white;"><?php if (isset($_SESSION['id'])){echo "GRAB A TABLE";} else {echo "Register";} ?></a>
	</div>
      </div>
     </div>
     <div class="container">
	<ul id="minitabs">
        <li><a href="../login" <?php if ($p==6){echo "class='active'";} ?>><?php if ($id>0 && isset($_SESSION['username'])){echo 'Logout ('.$_SESSION["username"].')';}elseif ($id>0){echo "Logout";} else {echo "Login";} ?></a></li>
	<li><a href="../" <?php if ($p==1){echo "class='active'";} ?>>Browse the Menu</a></li>
        <li><a href="../rate" <?php if ($p==2){echo "class='active'";} ?>><?php if ($id>0){echo "Rate a Dish";}?></a></li>
        <li><a href="../premium" <?php if ($p==3){echo "class='active'";} ?>><?php if ($id>0){echo "Go Premium<img src='../images/pink.jpg' style='vertical-align:text-bottom;height:13px;' />";}?></a></li>
        <li><a href="../inbox" <?php if ($p==4){echo "class='active'";} ?>><?php if ($id>0){echo "Mailbox";}?></a></li>
        <li><a href="../edit" <?php if ($p==5){echo "class='active'";} ?>><?php if ($id>0){echo "Profile";}?></a></li>
       <li><a href="../chat" <?php if ($p==7){echo "class='active'";} ?>><?php if ($id>0){echo "Live Chat";}?></a></li>
	</ul>
    </div>
    <div id="site_content">

