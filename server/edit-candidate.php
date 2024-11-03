<?php

session_start();

$host="localhost";
$user="root";
$password="";
$db="hts_db";



$data = mysqli_connect($host,$user,$password,$db);

if($data === false)
{
	die("connection error");
}

  $names = "";
  $username = "";
  $password = "";
  $usertype = "";

  $msg = "";

  if($_SERVER['REQUEST_METHOD'] =='GET') {
    if(!isset($_GET["id"]) && !isset($_SESSION["username"]) == "admin") {
        header("location: ./admin-home.php");
        exit;
    } 
    $id = $_GET["id"];

    $sql = "select * from candidates where id='$id' ";

	$result= mysqli_query($data, $sql);

	$row=mysqli_fetch_array($result);

    if(!$row) {
        header("location: ./admin-home.php");
        exit;
    }

    $names =$row['fullNames'];
    $username =$row['username'];
    $password =$row['password'];
    $usertype =$row['usertype'];
    $image = $row['profileIMG'];
    // debug
    echo($image);

} else {
    $folder = "./profiles/"; 
    $filename = uniqid() . '_' . basename($_FILES['candidateImage']['name']); // Generate unique filename
    $target_file = $folder . $filename;

    $id = $_POST['id'];
    $names = $_POST['fname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
    
    if (move_uploaded_file($_FILES['candidateImage']['tmp_name'], $target_file)) {
        // Image updated successfully
        $sql = "UPDATE IGNORE candidates SET username = '$username', password='$password', usertype='$usertype', fullNames='$names', profileIMG='$filename' WHERE id = $id ";
    
        $result = mysqli_query($data,$sql);
    
        if(!$result) {
            die("connection error");
        }
    
        $msg = "Record Updated";
        header("location: ./admin-home.php");
    } else {
        // Error uploading image
        $msg = "Image upload failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>EDIT CANDIDATE  |  LRC Voting Application</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../client/assests/css/update.css">
</head>
<body>
    <nav class="main-nav">
        <h1 class="welcome-msg">LRC Voting Application</h1>
        <ul class="menu">
            <li><a class="home" href="./logout.php">LOGOUT</a></li>
        </ul>
    </nav>
    <div class="login-form">
    <form class="login" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id;?>">
            <h2 class="form-header">
                UPDATE Candidate
            </h2>
            <p style="margin-top: 2rem; color: red;"></p>
            <label for="fullNames" style="margin-top: 2rem;">Full Names</label>
            <input type="text" name="fname" id="fname" value="<?php echo $names;?>">
            <label for="username">Username</label>
            <input type="text" name="username" id="lname" value="<?php echo $username; ?>">
            <label for="cimage">Candidate Image</label>
            <input type="file" name="candidateImage" value="<?php echo $image;?>">
            <label for="Password">Password</label>
            <input type="password" name="password" id="key" value="<?php echo $password;?>">
            <br>
            <label for="usertype">User Type</label>
            <select name="usertype" id="field">
                <option value="admin" selected>admin</option>
                <option value="user">user</option>
            </select>
            <button type="submit" class="signup-btn">Update</button>
        </form>
    </div>
</body>
</html>