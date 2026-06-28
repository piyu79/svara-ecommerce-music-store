<?php
if(isset($_POST['deregister_submit'])){

    // Load .env credentials
    $env = parse_ini_file(__DIR__ . '/.env');
    $con = mysqli_connect(
        $env['DB_HOST'],
        $env['DB_USER'],
        $env['DB_PASS'],
        $env['DB_NAME']
    );

    if(!$con){
        die("Connection failed: " . mysqli_connect_error());
    }

    $email = $_POST['delete_email'];

    // Check if user exists — prepared statement
    $checkStmt = mysqli_prepare($con, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);

    if(mysqli_num_rows($result) > 0){

        // Delete user — prepared statement
        $deleteStmt = mysqli_prepare($con, "DELETE FROM users WHERE email = ?");
        mysqli_stmt_bind_param($deleteStmt, "s", $email);

        if(mysqli_stmt_execute($deleteStmt)){
            echo "<h2>Account for " . htmlspecialchars($email) . " has been successfully deleted.</h2>";
            echo "<br><a href='registration1.html'>Back to Registration</a>";
        } else {
            echo "Error deleting record: " . mysqli_error($con);
        }

        mysqli_stmt_close($deleteStmt);
    } else {
        echo "<h2>No account found with that email address.</h2>";
        echo "<br><a href='de-register.html'>Go back</a>";
    }

    mysqli_stmt_close($checkStmt);
    mysqli_close($con);

} else {
    echo "Form not submitted correctly.";
}
?>