<?php 
require('banners.php');
$id = $_GET['banner_id'];
$banner = new Banners;
$banner->update($id);
location:"../banners/index.php";

?>