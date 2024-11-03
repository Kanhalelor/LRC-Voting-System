<?php
session_start();

// Check if the user is logged in and if they have the correct usertype
if (!isset($_SESSION["username"])) {
    header("location: ../users-login2.php");
    exit();
}

// Debugging: Check session variables
error_log("User ID in test4.php: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));

// Ensure user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    die("User ID not set in session. Please log in again.");
}

$host = "localhost";
$user = "root";
$password = "";
$db = "hts_db";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection error");
}

$msg = "";
$rows = [];

// Pagination variables
$limit = 10; // Number of candidates per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit;

// Fetch candidates that haven't been voted for
$votesQuery = "SELECT candidate_id FROM votes WHERE user_id = '" . $_SESSION['user_id'] . "'";
$votedCandidateIds = mysqli_query($data, $votesQuery);
$votedIds = [];
while ($row = mysqli_fetch_assoc($votedCandidateIds)) {
    $votedIds[] = $row['candidate_id']; // Assuming the column name in votes table is candidate_id
}

$votedIdsString = implode(',', $votedIds);
$votedCondition = !empty($votedIds) ? "AND id NOT IN ($votedIdsString)" : "";

// SQL to fetch candidates
$sql = "SELECT * FROM candidates WHERE usertype='user' $votedCondition LIMIT $limit OFFSET $offset";
$result = mysqli_query($data, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Count total candidates for pagination
$totalCandidatesResult = mysqli_query($data, "SELECT COUNT(*) as total FROM candidates WHERE usertype='user' $votedCondition");
$totalCandidates = mysqli_fetch_assoc($totalCandidatesResult)['total'];
$totalPages = ceil($totalCandidates / $limit);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $candidateId = $_POST['id'];

    // Update vote count in the database
    $update_sql = "UPDATE candidates SET total_votes = total_votes + 1 WHERE id = '$candidateId'";
    if (mysqli_query($data, $update_sql)) {
        // Save the vote
        $insertVote = "INSERT INTO votes (user_id, candidate_id) VALUES ('" . $_SESSION['user_id'] . "', '$candidateId')";
        mysqli_query($data, $insertVote);

        // Fetch new vote count
        $newVoteCount = mysqli_fetch_array(mysqli_query($data, "SELECT total_votes FROM candidates WHERE id = '$candidateId'"))['total_votes'];

        // Return the new vote count as JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'new_vote_count' => $newVoteCount]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error voting. Please try again.']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | HTS LRC Website</title>
    <link rel="stylesheet" href="./assests/css/results.css">
    <link href="https://fonts.googleapis.com/css2?family=Nerko+One&display=swap" rel="stylesheet">
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
        .call-out { font-size: 56px !important; }
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
    </style>
</head>
<body>
<?php if (isset($_SESSION['username'])): ?>
<div class="wrapper">
<div class="main-nav">
        <div class="menu">
          <h1 id="header">
          LRC Voting Application
          </h1>
          <div class="menu-a">
          <a href="../client/results.php" style="color: red;">Manage Voting</a>
            <a href="../server/logout.php" style="color: red;">Logout</a>
            <span id="active">Welcome User: <span style="color:chartreuse;"></span><?php echo $_SESSION["username"]?></span>
          </div>
        </div>
      </div>
    <div id="intro">
        <h1 class="call-out">Welcome to the HTS LRC Voting Portal!</h1>
        <p class="intro-p">Please review the candidate profiles and cast your vote thoughtfully.</p>
    </div>

    <div class="candidates-container">
    <?php foreach ($rows as $row): ?>
        <div class="candidate">
            <div class="candidate-title-container">
                <h1 class="candidate-title"><?php echo htmlspecialchars($row['fullNames']); ?></h1>
                <h1 class="candidate-title">Grade: <?php echo htmlspecialchars($row['grade']); ?></h1>
            </div>
            <div class="candidate-img-container">
                <img class="candidate-img" src="../server/profiles/<?php echo htmlspecialchars($row['profileIMG']); ?>" alt="candidate image">
            </div>
            <form onsubmit="castVote(event, '<?php echo $row['id']; ?>');">
                <button type="submit" class="signup-btn" id="vote_button_<?php echo $row['id']; ?>">
                    Vote Candidate
                </button>
            </form>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <?php if ($i == $page): ?>
                <strong><?php echo $i; ?></strong>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
</div>

<footer>Copyright &copy Liliana 2024</footer>

<script>
function castVote(event, candidateId) {
    event.preventDefault();

    const voteButton = document.getElementById('vote_button_' + candidateId);
    voteButton.disabled = true;

    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id: candidateId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Your vote has been successfully cast!");
            // Redirect to the next page after voting (optional)
            window.location.href = "?page=" + (parseInt(<?php echo $page; ?>) + 1);
        } else {
            alert('Error voting. Please try again.');
            voteButton.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating vote count');
        voteButton.disabled = false;
    });
}
</script>

<?php else: ?>
    <?php header("location: ./users-login.php", true, 302); exit(); ?>
<?php endif; ?>
</body>
</html>
