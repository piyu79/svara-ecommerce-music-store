<?php

if(isset($_POST['register_submit'])){

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm-password'];

// Check passwords
if($password !== $confirm_password){

echo "<h2>Passwords do not match!</h2>";
echo "<br><a href='registration1.html'>Go back to registration</a>";
exit();

}

$host = "localhost";
$username = "root";
$db_password = "";
$dbname = "priyaa1";

$con = mysqli_connect($host,$username,$db_password,$dbname);

if(!$con){
die("Connection failed: " . mysqli_connect_error());
}

// Check email
$checkEmail = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($con,$checkEmail);

if(mysqli_num_rows($result) > 0){

echo "<h2>This email is already registered!</h2>";
echo "<br><a href='registration1.html'>Go back</a>";

}
else{

$sql = "INSERT INTO users(name,email,password)
VALUES('$name','$email','$password')";

if(mysqli_query($con,$sql)){

echo "<h2>Registration Successful!</h2>";
echo "<br><a href='login1.html'>Click here to Login</a>";

}
else{

echo "Error: ".mysqli_error($con);

}

}

mysqli_close($con);

}

else{

echo "Form not submitted correctly.";

}

?>