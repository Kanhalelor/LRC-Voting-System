<?php


session_start();

if(!isset($_SESSION["username"])) {
    header("location: ../users-login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$db = "hts_db";

$data = mysqli_connect($host, $user, $password, $db);

if($data === false) {
    die("Connection error");
}

$msg = "";
$rows = [];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM candidates WHERE usertype='user' LIMIT 10";
    $result = mysqli_query($data, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote']) && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Increment the vote count for the candidate
    $update_sql = "UPDATE candidates SET total_votes = total_votes + 1 WHERE id = '$id'";
    if (mysqli_query($data, $update_sql)) {

        // prevent caching
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");

        header("Location: ./results.php");
        exit();
    } else {
        $msg = "Error voting. Please try again.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home  |  HTS LRC Website</title>
    <!-- <link rel="stylesheet" href="./assests/css/main.css"> -->
    <link rel="stylesheet" href="./assests/css/results.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nerko+One&display=swap" rel="stylesheet">
</head>
<style>
    .intro-p, .call-out {
        width: 100%;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: .8rem 0;
        background: #eedb06;
        border-bottom: 2px solid #121212;
        font-family: "Nerko One", cursive !important;
    }
    .call-out {
        font-size: 56px !important;
    }
</style>
<body>
<?php if (isset($_SESSION['username'])): ?>
<div class="wrapper">
    <div id="intro">
        <h1 class="call-out">Welcome to the HTS LRC Voting Portal!</h1>
        <p class="intro-p">
            We're excited to have you participate in this important event where you can make your voice heard. 
            Our candidates have been working hard to represent your interests, and now it's your turn to choose 
            who will lead. Voting is simple and secure. Please take a moment to review the candidate profiles and 
            cast your vote thoughtfully. Your participation is crucial in shaping the future of our school.
        </p>
    </div>
    <!-- Your navigation and other content -->
    <div class="candidates-container">
    <?php foreach($rows as $row): ?>
        <div class="candidate">
            <div class="candidate-title-container">
                <h1 class="candidate-title"><?php echo htmlspecialchars($row['fullNames']); ?></h1>
                <h1 class="candidate-title">Grade:<?php echo htmlspecialchars($row['grade']); ?></h1>
            </div>
            <div class="candidate-img-container">
                <img class="candidate-img" src="../server/profiles/<?php echo htmlspecialchars($row['profileIMG']); ?>" alt="candidate image">
            </div>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="submit" name="vote" value="Vote Candidate" class="signup-btn">
            </form>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<footer>
        Copyright &copy Liliana 2024
    </footer>
<?php else: ?>
    <?php header("location: ./users-login.php", true, 302); exit(); ?>
<?php endif; ?>

</body>
</html>
