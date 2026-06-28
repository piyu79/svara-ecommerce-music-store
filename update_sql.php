<?php

$host        = "localhost";
$username    = "root";
$db_password = "";
$dbname      = "priyaa1";

$con = mysqli_connect($host, $username, $db_password, $dbname);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['update_submit'])) {

    $email        = trim($_POST['email']);
    $new_name     = trim($_POST['new_name']);
    $new_password = $_POST['new_password'];

    // Hash the new password before storing
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepared statement — SQL injection safe
    $stmt = mysqli_prepare($con, "UPDATE users SET name = ?, password = ? WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "sss", $new_name, $hashed_password, $email);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<h2>Profile updated successfully for $email!</h2>";
            echo "<br><a href='registration1.html'>Back to Registration</a>";
        } else {
            echo "<h2>No account found with that email, or no new information provided.</h2>";
            echo "<br><a href='update.html'>Go back</a>";
        }
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>