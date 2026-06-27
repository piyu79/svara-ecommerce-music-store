<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Database connection
    $host       = "localhost";
    $username   = "root";
    $db_password = "";
    $dbname     = "priyaa1";

    $con = mysqli_connect($host, $username, $db_password, $dbname);
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert message into DB
    $sql = "INSERT INTO contact_messages (name, email, subject, message, submitted_at)
            VALUES ('$name', '$email', '$subject', '$message', NOW())";

    $result = mysqli_query($con, $sql);

    if ($result) {
        // Success - redirect back to contact page with success flag
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