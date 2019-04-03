<?php
        session_start();
        $id =0;
        $uid=0;
        if (isset($_SESSION['id'])){
                $id = $_SESSION['id'];
        }
        else {
                header("Location: login");
        }

                include('header.php');
                echo "<br /><br /><h3>Message sent.<h3><br /><br />";
                include('footer.php');
?>
