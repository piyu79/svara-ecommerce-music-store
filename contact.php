<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $host        = "localhost";
    $username    = "root";
    $db_password = "";
    $dbname      = "priyaa1";

    $con = mysqli_connect($host, $username, $db_password, $dbname);
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepared statement — SQL injection safe
    $stmt = mysqli_prepare($con, "INSERT INTO contact_messages (name, email, subject, message, submitted_at) VALUES (?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: contact1.html?status=success");
        exit();
    } else {
        echo "<h2>Something went wrong. Please try again.</h2>";
        echo "<a href='contact1.html'>Go back</a>";
    }

    mysqli_close($con);

} else {
    echo "Form was not submitted correctly.";
    echo "<a href='contact1.html'>Go back</a>";
}
?>