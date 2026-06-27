<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "priyaa1";

$con = mysqli_connect($servername,$username,$password,$dbname);

if(!$con){
die("Connection failed: ".mysqli_connect_error());
}

if(isset($_POST['update_submit'])){

$email = $_POST['email'];
$new_name = $_POST['new_name'];
$new_password = $_POST['new_password'];

$sql = "UPDATE users SET name='$new_name', password='$new_password' WHERE email='$email'";

if(mysqli_query($con,$sql)){

if(mysqli_affected_rows($con) > 0){

echo "<h2>Profile updated successfully for $email!</h2>";
echo "<br><a href='registration1.html'>Back to Registration</a>";

}
else{

echo "<h2>No account found with that email, or no new information provided.</h2>";
echo "<br><a href='update.html'>Go back</a>";

}

}
else{

echo "Error updating record: ".mysqli_error($con);

}

}

mysqli_close($con);

?>