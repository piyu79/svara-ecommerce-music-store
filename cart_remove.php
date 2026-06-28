<?php

header('Content-Type: application/json');

$host = "localhost";
$username = "root";
$db_password = "";
$dbname = "priyaa1";

$con = mysqli_connect($host, $username, $db_password, $dbname);

if(!$con){
    echo json_encode(["success" => false, "message" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

if(isset($_POST['cart_id'], $_POST['user_id'])){

    $cart_id = $_POST['cart_id'];
    $user_id = $_POST['user_id'];

    // user_id check included so a user can only delete their own cart rows
    $stmt = mysqli_prepare($con, "DELETE FROM cart WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $cart_id, $user_id);

    if(mysqli_stmt_execute($stmt)){
        if(mysqli_stmt_affected_rows($stmt) > 0){
            echo json_encode(["success" => true, "message" => "Item removed"]);
        } else {
            echo json_encode(["success" => false, "message" => "Item not found in your cart"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error removing item: " . mysqli_error($con)]);
    }

    mysqli_close($con);

} else {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
}

?>