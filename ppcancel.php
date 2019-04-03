<?php
        session_start();
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
        else {
                header("Location: login");
                die();
        }
        include("header.php");
?>

<h1>PayPal</h1>
<p>Cancelled</p>

<?php
 include("footer.php");
?>
