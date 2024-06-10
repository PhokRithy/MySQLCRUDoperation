<?php
    session_start();    
    if(isset($_POST['btnsubmit'])){
        
        $username = $_POST['tuser'];
        $password = $_POST['tpass'];
        if($username=="Admin" && $password=="123"){
            echo "Login Successful";
            $_SESSION['USERNAME'] = $username;
            header("location:starter.php");
        }else{
          echo "Username and Password Invalid";
        }
    }
?>



