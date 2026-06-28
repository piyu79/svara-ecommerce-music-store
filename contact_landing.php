<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name        = $_POST['name'];
    $email       = $_POST['email'];
    $subject     = $_POST['subject'];
    $message     = $_POST['message'];

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
        // Redirect back to contact.html (landing version) with success flag
        header("Location: contact.html?status=success");
        exit();
    } else {
        echo "<h2>Something went wrong. Please try again.</h2>";
        echo "<a href='contact.html'>Go back</a>";
    }

    mysqli_close($con);

} else {
    echo "Form was not submitted correctly.";
    echo "<a href='contact.html'>Go back</a>";
}
?>