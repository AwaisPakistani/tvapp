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
    public function change_password(){
        if (!isset($_SESSION['email'])) {
            header('location:../login.php');
        }
        require "../db.php";
        $email = $_SESSION['email'];
        if (isset($_POST['change_password'])) {
                $sql_all = "SELECT * FROM admins WHERE email='$email'";
                $result_all = $conn->query($sql_all);
                $row_all = $result_all->fetch_all(MYSQLI_ASSOC); 
                
                if (isset($_POST['new_password'])) {
                    if (isset($_POST['retype_password'])) {
                        $pass = $_POST['new_password'];
                        $retype_pass = $_POST['retype_password'];
                        if ($pass!=$retype_pass) {
                            $message=  "Error: Passwords are not matching.";
               
                            $_SESSION['error']=$message;
                            header('location:../users/change_password.php');
                        }else{
                            $password=password_hash($pass, PASSWORD_DEFAULT);
                            //$currentdatatime = date("Y/m/d");
                            $sql = "SELECT * FROM admins WHERE email='$email'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_all(MYSQLI_ASSOC);
                                $sql1 = "UPDATE admins SET password='$password' WHERE email='$email'";
                                if ($conn->query($sql1) === TRUE) {
                                    $message=  "Success: Password updated successfully.";
                                    $_SESSION['success']=$message;
                                    header('location:../index.php');
                                } else {
                                    echo "Error: " . $sql . "<br>" . $conn->error;
                                }
                            } else {
                                $message=  "Error: Records are empty.";
               
                                $_SESSION['error']=$message;
                                header('location:../users/change_password.php');
                            }
                            
                        }
                    
                    } else{
                        $message=  "Error: Re-type Password required.";
               
                        $_SESSION['error']=$message;
                        header('location:../users/change_password.php');
                    }
                } else{
                    $message=  "Error: Password required.";
               
                    $_SESSION['error']=$message;
                    header('location:../users/change_password.php');
                }
        }
        $conn->close();
    }
}
?>