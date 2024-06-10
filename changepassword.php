<?php
include("database.php");

if (isset($_POST['btn-update'])) {
    // Retrieve user input
    $old = mysqli_real_escape_string($con, $_POST['currentPassword']);
    $new = mysqli_real_escape_string($con, $_POST['newPassword']);
    $confirm = mysqli_real_escape_string($con, $_POST['confirmPassword']);

    // Check if new password and confirm password match
    if ($new === $confirm) {
        // Get the user's ID from the session or another secure source
        $username = $_SESSION['USERNAME']; // Example variable

        // Fetch the current password hash from the database
        $result = mysqli_query($con, "SELECT password FROM users WHERE username = '$username'");
        $user = mysqli_fetch_assoc($result);

        // Verify the old password
        if (password_verify($old, $user['password'])) {
            // Hash the new password
            $newPasswordHash = password_hash($new, PASSWORD_DEFAULT);

            // Update the password in the database
            $update = mysqli_query($con, "UPDATE users SET password = '$newPasswordHash' WHERE username = '$username'");

            if ($update) {
                echo "Password changed successfully!";
            } else {
                echo "Failed to change the password.";
            }
        } else {
            echo "The current password is incorrect.";
        }
    } else {
        echo "The new passwords do not match.";
    }
}
?>

<div class="col-12">
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">► Change Password</h3>
            <?php 
               if(isset($_SESSION['msg1'])){
                    echo $_SESSION['msg1'];
               }
               ?>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="post">
            <div class="card-body">
            <div class="form-group">
                <label for="exampleInputEmail1">Current Password</label>
                <input type="password" class="form-control" name="currentPassword" placeholder="Enter Username">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">New Password</label>
                <input type="password" class="form-control" name="newPassword" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Confirm Password</label>
                <input type="password" class="form-control" name="confirmPassword" placeholder="Password">
            </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
            <button type="submit" name="btn-update" class="btn btn-primary">Change</button>
            </div>
        </form>
        
        </div>
        <!-- /.card -->
    </div>
</div>