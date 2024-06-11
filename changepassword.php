<?php
include("database.php");

// Check if a session is already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['btn-update'])) {
    // Retrieve user input and sanitize it
    $old = trim($_POST['currentPassword']);
    $new = trim($_POST['newPassword']);
    $confirm = trim($_POST['confirmPassword']);

    // Check if new password and confirm password match
    if ($new === $confirm) {
        // Get the user's ID from the session or another secure source
        $username = $_SESSION['USERNAME']; // Example variable

        // Fetch the current password hash from the database using prepared statements
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify the old password
        if (password_verify($old, $user['password'])) {
            // Hash the new password
            $newPasswordHash = password_hash($new, PASSWORD_DEFAULT);

            // Update the password in the database using prepared statements
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $updateStmt->bind_param("ss", $newPasswordHash, $username);
            $updateSuccess = $updateStmt->execute();

            if ($updateSuccess) {
                $_SESSION['msg1'] = "Password changed successfully!";
            } else {
                $_SESSION['msg1'] = "Failed to change the password.";
            }
        } else {
            $_SESSION['msg1'] = "The current password is incorrect.";
        }
    } else {
        $_SESSION['msg1'] = "The new passwords do not match.";
    }

    // Redirect back to the form to display messages
    header("Location: change_password.php");
    exit();
}
?>

<div class="col-12">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">► Change Password</h3>
            <?php 
                if (isset($_SESSION['msg1'])) {
                    echo "<div class='alert alert-info'>" . htmlspecialchars($_SESSION['msg1']) . "</div>";
                    unset($_SESSION['msg1']); // Clear the message after displaying
                }
            ?>
        </div>
        <form method="post" onsubmit="return validatePasswordForm()">
            <div class="card-body">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" class="form-control" name="currentPassword" id="currentPassword" placeholder="Enter Current Password" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Enter New Password" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm New Password" required>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" name="btn-update" class="btn btn-primary">Change</button>
            </div>
        </form>
    </div>
</div>
<script>
function validatePasswordForm() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Check if the new password and confirm password match
    if (newPassword !== confirmPassword) {
        alert('New passwords do not match.');
        return false;
    }

    // Password strength check
    const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    if (!passwordPattern.test(newPassword)) {
        alert('Password must be at least 8 characters long and include at least one lowercase letter, one uppercase letter, and one number.');
        return false;
    }

    return true;
}
</script>
