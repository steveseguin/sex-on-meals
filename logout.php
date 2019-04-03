<?php

    session_start();
    unset($_SESSION['id']);
    $id = 0;
    $p = 0;
    include("header.php");

?>
<br />
<h3>Logged Out</h3>
<br />

<?php include("footer.php"); ?>
