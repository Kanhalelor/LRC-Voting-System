<?php
session_start();

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

// Fetch candidates
$sql = "SELECT * FROM candidates WHERE usertype='user' LIMIT $limit OFFSET $offset";
$result = mysqli_query($data, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Count total candidates for pagination
$totalCandidatesResult = mysqli_query($data, "SELECT COUNT(*) as total FROM candidates WHERE usertype='user'");
$totalCandidates = mysqli_fetch_assoc($totalCandidatesResult)['total'];
$totalPages = ceil($totalCandidates / $limit);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $candidateId = $_POST['id'];

    // Update vote count in the database
    $update_sql = "UPDATE candidates SET total_votes = total_votes + 1 WHERE id = '$candidateId'";
    if (mysqli_query($data, $update_sql)) {
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
    </style>
</head>
<body>
<div class="wrapper">
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
</body>
</html>
