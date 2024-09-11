<?php

$host="localhost";
$user="root";
$password="";
$db="hts_db";

session_start();


$data=mysqli_connect($host,$user,$password,$db);

if($data===false)
{
	die("connection error");
}

// global var msg
$msg = "";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
	$username = $_POST["username"];
	$password = $_POST["password"];


	$sql = "select * from candidates where username='".$username."' AND password='".$password."' ";
    

	$result = mysqli_query($data,$sql);

	$row=mysqli_fetch_array($result);
    // print_r($row);

    if($row["usertype"]=="user")
	{	
            $_SESSION["username"]=$row["username"];
            header("location: ./client/results-test.php");
	}

	elseif($row["usertype"]=="admin")
	{

		$_SESSION["username"]=$row["username"];
		
		header("location: ./server/admin-home.php");
	}

	else
	{
        echo("<script>alert('username or password incorrect')</script>");
		header('location: ./users-login.php');
        $msg =  "username or password incorrect";
        
	}
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login  | HTS LRC Website</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./client/assests/css/login.css">
    <link rel="stylesheet" href="./client/assests/css/main.css">
</head>
<body>
    <nav class="main-nav">
        <h1 class="welcome-msg">LRC Voting Application</h1>
        <ul class="menu">
            <li><a class="sign-up" href="./client/index.php">HOME</a></li>
        </ul>
    </nav>
    <div class="login-form">
        <form method="POST" class="login" action="./users-login.php">
            <h2 class="form-header">
                LOGIN
            </h2>
            <p style="color: tomato;"><?php echo $msg;?></p>
            <label for="username">Username</label>
            <input type="text" name="username" id="mail" required>
            <label for="Password">Password</label>
            <input type="password" name="password" id="key" required onblur="validatePassword(this.value)">
            <button  class="login-btn" type="submit">LOGIN</button>
        </form>
        <p class="forgot-p-p">Forgot Password?</p>
        <p class="mail-to-admin"><a href="mailto:lrcsystem2024@gmail.com">Click here to contact System Administrator!</a></p>
    </div>
    <script>
function validatePassword(password) {
  // Regular expression to check for at least 6 characters and a combination of letters and numbers
  const regex = /^(?=.*[a-zA-Z])(?=.*\d).{6,}$/;

  const passwordInput = document.getElementById("key");

  if (!regex.test(password)) {
    alert("Password must be at least 6 characters long and contain both letters and numbers.");
    passwordInput.style.borderColor = "red"; // Add visual indication of error
  } else {
    passwordInput.style.borderColor = ""; // Reset border color to default
  }
}
</script>
</body>
</html>
