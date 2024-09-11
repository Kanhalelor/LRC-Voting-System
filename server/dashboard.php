<?php
session_start();

if(!isset($_SESSION["username"]))
{
	header("location: ../users-login.php");
    
}

$host="localhost";
$user="root";
$password="";
$db="hts_db";


$data = mysqli_connect($host,$user,$password,$db);

if($data === false)
{
	die("connection error");
}


if($_SERVER['REQUEST_METHOD'] =='GET')
{
	$sql="select * from candidates";

	$result = mysqli_query($data, $sql);

	$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

}

$msg = "";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home  |  HTS LRC Website</title>
    <link rel="stylesheet" href="./assests/css/main.css">
    <link rel="stylesheet" href="./assests/css/results.css">
  </head>
  <body>
  <?php if (isset($_SESSION['username'])): ?>
    <div class="wrapper">
      <div class="ad-space-1"></div>
      <div class="main-nav">
        <div class="logo-container">
          <img
            src="./assets/hsps-logo.png"
            alt="logo"
            class="logo"
          />
        </div>
        <div class="menu">
          <div class="menu-a">
            <a href="#">ABOUT</a>
            <a href="#">NEWS</a>
            <a href="#footer">CONTACT</a>
            <a href="#">LIFE AT HSPS</a>
            <a href="./gallery.php">GALLERY</a>
            <a href="./shop.php">SHOP</a>
            <a href="./login.php">HSPS LMS</a>
            <a href="./logout.php" class="logut-btn sign-up">LOGOUT</a>
            <a class="active" href="#"><?php echo $_SESSION["username"];?></a>
          </div>
        </div>
      </div>
      <div class="courses-headings">
        <h1 class="header-1">Welcome to HTS LRC Voting Portal.</h1>
        <h3 class="header-2">Please cast your vote. The system will automatically log you out after voting.</h3>
      </div>
      <?php if(empty($rows)): ?>
        <p> There are no candidates available at the moment!</p>
        <p> Please check back latter.</p>
      <?php endif;?>
      <div class="courses-container">
      <?php foreach($rows as $row): ?>
        <div class="course">
            <div class="course-title-container">
                <h1 class="course-title"><?php echo $row['fullNames']; ?></h1>
            </div>
            <div class="course-img-container">
              <img class="course-img" src="assets/<?php echo $row['profileIMG']; ?>"></image>
            </div>
            <div class="enrollment-div">
              <a class="view-course" href="<?php echo $row['total_votes']; ?>">View</a>
            </div>
            <input type="submit" name="vote" value="Vote Candidate" class="signup-btn">
        </div>
        <?php endforeach;?>
    <div id="contact"></div>
    <?php else:?>
        <?php header("location: ./users-login.php", true, 302);?>
    <?php endif; ?>
    <script src="./assets/js/menu-bar.js"></script>
  </body>
</html>