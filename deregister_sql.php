<?php

if(isset($_POST['deregister_submit'])){

$email = $_POST['delete_email'];

$con = mysqli_connect("localhost","root","","priyaa1");

if(!$con){
die("Connection failed: ".mysqli_connect_error());
}

$email = mysqli_real_escape_string($con,$email);

$checkUser = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($con,$checkUser);

if(mysqli_num_rows($result) > 0){

$deleteQuery = "DELETE FROM users WHERE email='$email'";

if(mysqli_query($con,$deleteQuery)){

echo "<h2>Account for $email has been successfully deleted.</h2>";
echo "<br><a href='registration1.html'>Back to Registration</a>";

}
else{

echo "Error deleting record: ".mysqli_error($con);

}

}
else{

echo "<h2>No account found with that email address.</h2>";
echo "<br><a href='de-register.html'>Go back</a>";

}

mysqli_close($con);

}
else{

echo "Form not submitted correctly.";

}

?>