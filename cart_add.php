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

if(isset($_POST['user_id'], $_POST['product_name'], $_POST['product_price'])){

    $user_id = $_POST['user_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = isset($_POST['product_image']) ? $_POST['product_image'] : '';

    // Check if this product already exists in the user's cart
    $checkStmt = mysqli_prepare($con, "SELECT id, quantity FROM cart WHERE user_id = ? AND product_name = ?");
    mysqli_stmt_bind_param($checkStmt, "is", $user_id, $product_name);
    mysqli_stmt_execute($checkStmt);
    $result = mysqli_stmt_get_result($checkStmt);

    if($row = mysqli_fetch_assoc($result)){
        // Already in cart, bump quantity
        $newQty = $row['quantity'] + 1;
        $updateStmt = mysqli_prepare($con, "UPDATE cart SET quantity = ? WHERE id = ?");
        mysqli_stmt_bind_param($updateStmt, "ii", $newQty, $row['id']);
        if(mysqli_stmt_execute($updateStmt)){
            echo json_encode(["success" => true, "message" => "Quantity updated", "quantity" => $newQty]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating cart: " . mysqli_error($con)]);
        }
    } else {
        // New item, insert it
        $insertStmt = mysqli_prepare($con, "INSERT INTO cart (user_id, product_name, product_price, product_image, quantity) VALUES (?, ?, ?, ?, 1)");
        mysqli_stmt_bind_param($insertStmt, "isss", $user_id, $product_name, $product_price, $product_image);
        if(mysqli_stmt_execute($insertStmt)){
            echo json_encode(["success" => true, "message" => "Item added to cart"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error adding to cart: " . mysqli_error($con)]);
        }
    }

    mysqli_close($con);

} else {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
}

?>