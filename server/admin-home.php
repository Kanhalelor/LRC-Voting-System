<?php
session_start();

$host="localhost";
$user="root";
$password="";
$db="hts_db";

if(!isset($_SESSION["username"]) == "admin")
{
	header("location: users-login.php");
}
if(isset($_SESSION["username"]) == "admin") {
  $data = mysqli_connect($host,$user,$password,$db);

  if($data === false)
  {
    die("connection error");
  }
  
  
  if($_SERVER['REQUEST_METHOD'] =='GET' || isset($_POST['view-users']))
  {
    $sql="select * from candidates where usertype='user' ";
  
    $result = mysqli_query($data, $sql);
  
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
  
  }
  
  $msg = "";
  
  if(isset($_POST['add-user-btn'])) {

    $folder = "./profiles/"; 
    $filename = uniqid() . '_' . basename($_FILES['candidateImage']['name']); // Generate unique filename
    $target_file = $folder . $filename;
  

// Move uploaded file to the specified directory
    if (move_uploaded_file($_FILES['candidateImage']['tmp_name'], $target_file)) {
    // Image uploaded successfully
    $sql = "INSERT INTO candidates (password, username, usertype, fullNames, profileIMG) VALUES ('$password', '$username', '$usertype', '$names', '$filename')";

} else {
    // Error uploading image
    $msg = "Image upload failed.";
}

    // $filename = $_FILES["candidateImage"]["name"];
    // $tempname = $_FILES["candidateImage"]["tmp_name"];


    $names = $_POST['fname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];
  
  
      $sql = "INSERT INTO candidates (password, username, usertype, fullNames, profileIMG) VALUES ('$password', '$username', '$usertype', '$names', '$filename')";
  
      if(mysqli_query($data, $sql)) {   
        echo "<script type='js'>alert('New record successfully added!');";
        header('location: ./admin-home.php');
      } elseif( $mysqli_query($data,$sql)->errorno == 1062) {
          $msg = "Username already taken.";
      } else {
        $msg = "";
      }
  }
  
}

?>
<!-- html -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Home  |  HTS LRC Website</title>
    <!-- <link rel="stylesheet" href="../client/assests/css/results.css"> -->
    <link rel="stylesheet" href="../client/assests/css/admin-home.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nerko+One&display=swap" rel="stylesheet">
    <style>
    .action {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .action > a {
      margin: .3rem 0 0 0;
    }
    .menu {
      display: flex;
      background: #eedb06;
      justify-content: space-between;
      align-items: center;
      padding: 0 .4rem;
    }
  .admin-intro {
  display: flex;
  justify-content: center;
  flex-direction: column;
  align-items: center;
  }   
    .admin-intro > h {
      font-family: Verdana, Geneva, Tahoma, sans-serif;
      font-size: 36px;
      font-weight: 800;
  }
  .admin-intro > p {
      width: 60%;
      text-align: justify;
      margin: 1rem 0;
      background: #e0e0e0;
      font-family: "Nerko One", cursive;
      padding: .4rem;
  }
.menu-a {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 1.5rem;
  text-decoration: none;
  color: #121212;
  font-size: 16px;
  font-weight: 600;
  padding: 0.6rem 0.2rem;
  cursor: pointer;
  font-family: "Nerko One", cursive;
}
.menu-a a {
  text-decoration: none;
  font-size: 26px;
  background: #e0e0e0;
  font-weight: 600;
  padding: .2rem .3rem;
  border-radius: 2px;
}
.menu-a a:hover {
  color: #121212;
}
.menu-a > a, span {
  margin: 0 1rem 0 0;
}

.title {
  font-size: 60px;
  font-weight: 800;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .admin-notes {
    font-size: 25px;
    font-weight: 600;
  }
  #header {
    font-family: "Nerko One", cursive;
    font-size: 50px;
    font-weight: 600;
  }
    </style>
  </head>
  <body>
  <?php if (isset($_SESSION['username'])): ?>
      <div class="main-nav">
        <div class="menu">
          <h1 id="header">
          LRC Voting Application
          </h1>
          <div class="menu-a">
          <a href="../client/results.php" style="color: red;">Manage Voting</a>
            <a href="./logout.php" style="color: red;">Logout</a>
            <span id="active">Welcome Admin: <span style="color:chartreuse;"></span><?php echo $_SESSION["username"]?></span>
          </div>
        </div>
      </div>
      <div class="admin-intro">
        <h1 class="title">Admin Dashboard</h1>
        <p class="admin-notes">Welcome, Admin! Manage candidates, monitor votes, and ensure a smooth voting experience for all users.</p>
        </div>
      <div class="users-container">
      <div class="users">
        <?php if(empty($rows)): ?>
          <p> There are no candidate records found </p>
        <?php endif;?>
        <?php foreach($rows as $row): ?>
        <table class="styled-table users-table">
          <tr>
          <th>#</th>
          <th>Full Names(s)</th>
          <th>Username</th>
          <th>User Type</th>
          <th>Photo</th>
          <th>Vote Count</th>
          <th>Action</th>
          </tr>
          <tr>
            <td><?php echo $row['id'] ?></td>
            <td><?php echo $row['fullNames'] ?></td>
            <td><?php echo $row['username'] ?></td>
            <td><?php echo $row['usertype'] ?></td>
            <?php
            $imagePath = "./profiles/" . $row['profileIMG'];
            $imageData = base64_encode(file_get_contents($imagePath));
            ?>
            <td class="item-img"><img src="data:image/jpeg;base64,<?php echo $imageData;?>" alt="photo"></td>
            <td class="vote-count"><?php echo $row['total_votes'] ?></td>
            <td class="action">
              <a href="./edit-candidate.php?id=<?php echo $row['id'] ?>" class="edit-user-btn">Edit</a>
              <a href="./remove-candidate.php?id=<?php echo $row['id'] ?>" class="del-user-btn">Remove</a>
            </td>
          </tr>
      </table>
      <?php endforeach;?>
    </div>
    <div class="add-user-modal">
    <form class="login" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="size" value="1000000">
            <h2 class="form-header">
                Add New Candidate
            </h2>
            <p style="margin-top: 2rem; color: red;"><?php echo $msg ?></p>
            <label for="fullNames" style="margin-top: 2rem;">Full Names</label>
            <input type="text" name="fname" id="fname" require>
            <label for="username">Username</label>
            <input type="text" name="username" id="lname" require>
            <label for="image">Image</label>
            <input type="file" name="candidateImage" value="">
            <label for="Password">Password</label>
            <input type="password" name="password" id="key" required>
            <br>
            <label for="usertype">User Type</label>
            <select name="usertype" id="field" required>
                <option value="admin" selected>admin</option>
                <option value="user">user</option>
            </select>
            <input type="submit" name="add-user-btn" value="Add Candidate" class="signup-btn">
        </form>

    </div>
  </div>
  </div>
    <div id="contact"></div>
    <?php else:?>
        <?php header("location: ./logout.php", true, 302);?>
    <?php endif; ?>
  </body>
</html>