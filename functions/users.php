<?php 
class Users {
    
    public function __construct(){
        // session_start();
    }
    public function index(){
        //
    }
    public function login(){
            include './db.php';
           
            // thumbnails  folder name
            if (isset($_POST['login'])) {
                $mail = $_POST['email'];
                $pass = $_POST['password'];
                //die();
                        if (isset($mail)) {
                            $email = $mail;    
                        } else {
                            echo "Please enter email";
                            die();
                            // echo "<script>alert('Enter Email')</script>";
                            // header('location:./login.php');
                        }
                        if (isset($_POST['password'])) {
                            $password = $pass;
                        } else {
                            echo "Please enter password";
                            die();
                            // echo "<script>alert('Enter password')</script>";
                            // header('location:./login.php');
                        }
                        // echo $email;
                        // echo " ";
                        // echo $password;
                        // die();
                        
                        
                        //$currentdatatime = date("Y/m/d");
                        
                        $sql = "SELECT * FROM admins WHERE email= '$email'";
                        
                        $result = $conn->query($sql);
                        //echo $result->num_rows; die();
                        if ($result->num_rows > 0) {
                                // output data of each row
                                    // output data of each row
                                $row = $result->fetch_all(MYSQLI_ASSOC);
                                $password_hashed = $row[0]['password'];
                                if(password_verify($_POST['password'],$password_hashed)){
                                    
                                    $_SESSION['email']=$email;
                                    // header('location:./functions/sessions.php?email='.$email);
                                    // $message_login =  "Login successfully";
                                    // echo $message_login;
                                    // echo "<script>alert($message_login)</script>";
                                    // exit(); 
                                }else{
                                    $message_emailexist =  "Email or password is incorrect";
                                    echo $message_emailexist;
                                    // echo "<script>alert($message_emailexist)</script>";
                                    // exit(); 
                                }
                            
                        } else {
                            echo "Email does not exist";
                            die();
                            // $message_emailexist =  "Email does not exist";
                            // echo "<script>alert($message_emailexist)</script>";
                            // exit(); 
                        }
                        
                        $conn->close();
            }

    }
    public function register(){
        //
    }
    public function db(){
        $db =include '../db.php';
        return $db;
    }
}
?>