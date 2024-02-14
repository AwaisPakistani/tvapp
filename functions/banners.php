<?php
require_once('../db.php'); 
class Banners {

    public function __construct()
    {
        if(!isset($_SESSION['email'])){
            header('location:../login.php');
        }
    }
    public function index(){
        include '../db.php';
        $sql = "SELECT * FROM banner_images";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result;
        } else {
           echo "No record found";
        }

    }

    public function store(){
        include '../db.php';
     
      
      if (isset($_POST['add_banner']))
        {
           
            $fcheck =$_FILES['banner_image']['name'];

            $query = "SELECT COUNT(*) AS total_count FROM banner_images WHERE image = '$fcheck'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $count = $row['total_count'];
            
            if (isset($_FILES['banner_image']['name'])) {
              // Get image information
              $image_name = $_FILES['banner_image']['name'];
              $image_tmp_name = $_FILES['banner_image']['tmp_name'];
              $image_type = $_FILES['banner_image']['type'];
              $image_size = $_FILES['banner_image']['size'];
              // Validate image
              if ($image_size > 500000) { // 500kb limit
        
                echo "<script>alert('Error: Image size exceeds limit')</script>";
              }
               elseif ($count > 0) {    
                  $message_exist_entry=  "Error: An Image already exists.";
               
                  $_SESSION['error']=$message_exist_entry;
                  header('location:../banners/index.php');
              }
              elseif (!in_array($image_type, ['image/png', 'image/jpeg'])) {
               $message_type=  "Error: Only PNG and JPEG images allowed.";
               
                  $_SESSION['error']=$message_type;
                  header('location:../banners/index.php');
              } else {
                // Generate unique filename
                $filename = uniqid() . '.' . pathinfo($image_name, PATHINFO_EXTENSION);
                // Move uploaded image to a designated folder
                $upload_path = '../uploads/' . $filename;
                move_uploaded_file($image_tmp_name, $upload_path);
                // Connect to database
               
                // Check database connection
                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }
                // Prepare SQL query
                  $sql = "INSERT INTO banner_images (image, image_path) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                // Bind parameters
                $stmt->bind_param("ss", $image_name, $upload_path);
                // Execute query
                if ($stmt->execute()) {
                  $message_success= "Banner created successfully.";
                  $_SESSION['success']=$message_success;
                  header('location:../banners/index.php');
                } else {
                  echo "Error: " . $conn->error;
                }
                // Close connections
                $stmt->close();
                $conn->close();
              }
            } else {
              $message =  "No image uploaded. You named wrong or other reason";
              echo "<script>alert($message)</script>";
            }
           
        }
    } 

    public function destroy(){
            include('../db.php');

            $id = $_GET['banner_id'];

            // Fetch entry details
            $sql = "SELECT image_path FROM banner_images WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $entry = $result->fetch_assoc();
            // Check if entry exists
            if (!$entry) {
                echo json_encode(['success' => false, 'message' => 'Entry not found']);
                exit;
            }
            // Delete image
            $image_path = $entry['image_path'];
            if (file_exists($image_path)) {
                if (!unlink($image_path)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete image']);
                    exit;
                }
            }
            // Delete entry
            $sql = "DELETE FROM banner_images WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            // Check if deletion successful
            if ($stmt->affected_rows > 0) { 
                  $message_delete= "Entry and Image deleted successfully.";
                  $_SESSION['success']=$message_delete;
                  header('location:../banners/index.php');
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete entry']);
            }
            $conn->close();
    }

    public function edit(){
        include '../db.php';
        $id = $_GET['banner_id'];
        $sql = "SELECT * FROM banner_images WHERE id='$id'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // Output data of each row
           return $result;
        } else {
             echo "0 results";
        }
   
    }

    public function update($id){
       if (isset($_POST['update_banner'])) {
           // update code
            include('../db.php');

            // image existance
            $fcheck =$_FILES['banner_image']['name'];

            $query = "SELECT COUNT(*) AS total_count FROM banner_images WHERE id = '$fcheck'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($result);
            $count = $row['total_count'];


            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate file upload
                if (isset($_FILES["banner_image"]) && $_FILES["banner_image"]["error"] == 0) {
                    $allowed_mime_types = array("image/jpeg", "image/png", "image/gif");
                    $max_file_size = 2097152; // 2MB
                    $file_name = $_FILES["banner_image"]["name"];
                    $file_mime_type = $_FILES["banner_image"]["type"];
                    $file_size = $_FILES["banner_image"]["size"];
                    // Check file type
                    if (!in_array($file_mime_type, $allowed_mime_types)) {
                       
                        $message= "Invalid file type. Only JPEG, PNG, and GIF are allowed..";
                        $_SESSION['error']=$message;
                        header('location:../banners/index.php');
                    }
                    // Check file size
                    if ($file_size > $max_file_size) { 
                        $message= "File size exceeds the limit.";
                        $_SESSION['error']=$message;
                        header('location:../banners/index.php');
                        
                    } else {
                        // Generate unique filename
                        $new_filename = "../uploads/".$file_name;  
                        // Upload file
                        $sqlimg = "SELECT image_path FROM banner_images WHERE id = ?";
                        $stmt = $conn->prepare($sqlimg);
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $entry = $result->fetch_assoc();
                        // Check if entry exists
                        if (!$entry) {
                            $message= "Entry not found.";
                            $_SESSION['error']=$message;
                            header('location:../banners/index.php');
                        }
                        // Delete image
                        $imgpath = $entry['image_path'];
                        if (file_exists($imgpath)) {
                            if (!unlink($imgpath)) {
                                echo json_encode(['success' => false, 'message' => 'Failed to delete image']);
                                exit;
                            }
                        }
                        if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], "../uploads/" . $new_filename)) {
                           
                            $query = "UPDATE banner_images SET image = '$file_name', image_path = '$new_filename' WHERE id = $id";
                            // Execute update query
                            // ...
                            if ($conn->query($query)) {
                                $message= "Record updated successfully.";
                                $_SESSION['success']=$message;
                                header('location:../banners/index.php');
                            }elseif ($count > 0) {
                                $message= "Error: Image already exists.";
                                $_SESSION['error']=$message;
                                header('location:../banners/index.php');
                            } else {
                                $message= "Error updating record: " . $conn->error;
                                $_SESSION['error']=$message;
                                header('location:../banners/index.php');
                            }
                        } else {
                            $message= "Error uploading image.";
                            $_SESSION['error']=$message;
                            header('location:../banners/index.php');
                        }
                    }
                } else {
                    
                    $message= "No file uploaded.";
                    $_SESSION['error']=$message;
                    header('location:../banners/index.php');
                    
                }
            }
        }
    }



}
?>