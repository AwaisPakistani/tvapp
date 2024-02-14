<?php 
require('banners.php');
$delete_banner = new Banners;
$delete_banner->destroy();
?>