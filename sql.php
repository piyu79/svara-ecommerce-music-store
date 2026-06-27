<?php
session_start();

if (isset($_POST['email'])) {

    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $host        = "localhost";
    $username    = "root";
    $db_password = "";
    $dbname      = "priyaa1";

    $con = mysqli_connect($host, $username, $db_password, $dbname);
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch user by email only — then verify password with password_verify()
    $stmt = mysqli_prepare($con, "SELECT id, name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name']  = $row['name'];
            $_SESSION['user_id']    = $row['id'];
            echo "Login Successful|" . $row['name'] . "|" . $row['id'];
            exit();
        } else {
            echo "<h2>Invalid email or password</h2>";
            echo "<a href='login1.html'>Go back to Login</a>";
        }
    } else {
        echo "<h2>Invalid email or password</h2>";
        echo "<a href='login1.html'>Go back to Login</a>";
    }

    mysqli_close($con);

} else {
    echo "The form was not submitted correctly. Please go back to the login page.";
}
?>