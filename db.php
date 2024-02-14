<?php
// Database connection settings
// $servername = "localhost";
// $username="pakist13_tvuser";
// $password="livetv@user";
// $database = "pakist13_livetv";
$servername = "localhost";
$username="root";
$password="";
$database = "onestoptv";

$conn=mysqli_connect ($servername,$username,$password);
mysqli_select_db($conn,$database);
if($conn->connect_error){
    //    echo"Connection Failed".$conn->connect_error;
    die("Connection Failed :".$conn->connect_error);
}
else{
  
  //  echo"Connection Successful";
}
?>