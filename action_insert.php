<?php
// require('database.php');

// if (isset($_POST['submit'])) {
//     $users = $_POST['uname'];
//     $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);

//     // Insert user data into the 'users' table
//     $query = "INSERT INTO users (username, password) VALUES ('$users', '$password')";
//     //echo $query;
//     mysqli_query($conn, $query) or die(mysqli_error($conn));

//     // Redirect to login page or show a success message
//     header('Location: login.php');
//     exit;
// }
?>

<?php
require('database.php');

if (isset($_POST['submit'])) {
  $users = $_POST['uname'];
  $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
  $status = "active"; // Assuming "active" is the default status

  // Insert user data into the 'users' table
  $query = "INSERT INTO users (username, password, status) VALUES ('$users', '$password', '$status')";

  // Execute the query and handle errors
  if (mysqli_query($conn, $query)) {
    // User registration successful
    header('Location: login.php');
    exit;
  } else {
    die("Error: " . mysqli_error($conn));
  }
}
?>