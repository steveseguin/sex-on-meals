<?php 
        session_start();
        $id=0;
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
        $p=3;
        include("header.php");
?>
<div>
<h3>For as little as $1,</h3> <h1>verify your identify while supporting breast cancer research!</h1>
<img src='../images/paypal_button.gif' style='float:right;display:inline-block;margin:10px;' /><p>Submit any amount you wish, from $1 to $10,000; all proceeds will be donated to cancer research benefiting women. By making your payment through PayPal, we can privately and securely verify your name and address. Verification helps to keep the community safe; enhancing the confidence of others that you are not a spammer, scammer or deviant. Your donation amount will also be reflected on your public profile, which is a great way to showcase your personal wealth to potential dinner guests.</p> <p>We take your privacy very seriously, but we take your safety even more so; we are fully committed to cooperating with the authorities if it ensures the safety of our members.</p>
<div style='height:300px'><center>
<form action='../buy' method='POST'><font size="300%">$</font>
<input type="number" id='amount' name='amount' min="1" max="10000" step="1" value="1" pattern="[0-9]*" style='height:40px;width:130px;font-size:300%;' /><font style="font-size:220%;"> </font>
<input type="submit" style='font-size:240%;' value='Go' />
<img src='../images/ribbon.gif' style='vertical-align:middle;display:inline-block;' />
</form>
</center></div>
<?php include("footer.php"); ?>

