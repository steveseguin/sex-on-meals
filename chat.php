<?php 
session_start();
        $id =0;
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }

include('header.php');

?>
<br /><br />

<?php
include('footer.php');
?>
