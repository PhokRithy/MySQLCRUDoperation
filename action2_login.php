<?php
include("database.php");
session_start();
$user = $_POST['tuser'];
$pwd = $_POST['tpass'];
 
$str = "SELECT * FROM users WHERE username='$user'";
//echo $str;
$query = mysqli_query($con,$str) or die(mysqli_error($con));
$user = mysqli_fetch_assoc($query);
 
if ($user && password_verify($pwd, $user['password'])) {
    $_SESSION['USERNAME'] = $user['username'];
    header("location:starter.php");
    exit;
 
} else {
    echo "Invalid username or password.";
}
 
?>


