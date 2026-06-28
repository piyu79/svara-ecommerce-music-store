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

if(isset($_GET['user_id'])){

    $user_id = $_GET['user_id'];

    $stmt = mysqli_prepare($con, "SELECT id, product_name, product_price, product_image, quantity FROM cart WHERE user_id = ? ORDER BY added_at DESC");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items = [];
    while($row = mysqli_fetch_assoc($result)){
        $items[] = $row;
    }

    echo json_encode(["success" => true, "items" => $items]);

    mysqli_close($con);

} else {
    echo json_encode(["success" => false, "message" => "Missing user_id."]);
}

?>