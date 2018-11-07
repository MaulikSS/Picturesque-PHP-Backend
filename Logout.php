<?php
include ("DBconnect.php");
include ("functions.php");
session_start();

if(!isset($_SESSION['username'])){
    redirect('Login.php');
}
else{
    session_unset();
    session_destroy();
    redirect('Login.php');
}

?>
