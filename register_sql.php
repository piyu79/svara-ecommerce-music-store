<?php

if (isset($_POST['register_submit'])) {

    $name             = trim($_POST['name']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Check passwords match
    if ($password !== $confirm_password) {
        echo "<h2>Passwords do not match!</h2>";
        echo "<br><a href='registration1.html'>Go back to registration</a>";
        exit();
    }

    $host        = "localhost";
    $username    = "root";
    $db_password = "";
    $dbname      = "priyaa1";

    $con = mysqli_connect($host, $username, $db_password, $dbname);
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if email already exists — prepared statement
    $checkStmt = mysqli_prepare($con, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        echo "<h2>This email is already registered!</h2>";
        echo "<br><a href='registration1.html'>Go back</a>";
    } else {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insertStmt = mysqli_prepare($con, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($insertStmt, "sss", $name, $email, $hashed_password);

        if (mysqli_stmt_execute($insertStmt)) {
            echo "<h2>Registration Successful!</h2>";
            echo "<br><a href='login1.html'>Click here to Login</a>";
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }

    mysqli_close($con);

} else {
    echo "Form not submitted correctly.";
}
?>