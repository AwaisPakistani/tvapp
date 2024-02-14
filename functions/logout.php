<?php 
session_start();
require_once('../db.php');
if (isset($_SESSION['email'])) {
    session_destroy();
    header('location:../login.php');
}else{
    header('location:../login.php');
}
?>