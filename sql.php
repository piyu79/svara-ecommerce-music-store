<?php
session_start(); // Start session

if(isset($_POST['email'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $host = "localhost";
    $username = "root";
    $db_password = "";
    $dbname = "priyaa1";

    $con = mysqli_connect($host, $username, $db_password, $dbname);

    if(!$con){
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if email and password match
    $stmt = mysqli_prepare($con, "SELECT * FROM users WHERE email=? AND password=?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) === 1){
    // Login successful
    $row = mysqli_fetch_assoc($result);
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $row['name'];
    $_SESSION['user_id'] = $row['id'];
    echo "Login Successful|" . $row['name'] . "|" . $row['id'];
    exit();

    } else {

        // Login failed (Echo instead of alert)
        echo "<h2>Invalid email or password</h2>";
        echo "<a href='login1.html'>Go back to Login</a>";

    }

    mysqli_close($con);

} else {

    echo "The form was not submitted correctly. Please go back to the login page.";

}
?>