<?php 
session_abort();
$email = $_GET['email'];
$_SESSION['email']=$email;
header('location:../index.php');
?>