<?php
session_start();

// Initialize the voted candidates array in the session if it doesn't exist
if (!isset($_SESSION['voted_candidates'])) {
    $_SESSION['voted_candidates'] = [];
}

if (!isset($_SESSION["username"])) {
    header("location: ../users-login.php");
    exit();
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

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM candidates WHERE usertype='user' LIMIT 10";
    $result = mysqli_query($data, $sql);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $candidateId = $_POST['id'];

    // Check if the user has already voted for this candidate
    if (in_array($candidateId, $_SESSION['voted_candidates'])) {
        echo json_encode(['success' => false, 'message' => 'You have already voted for this candidate.']);
        exit();
    }

    // Update vote count in the database
    $update_sql = "UPDATE candidates SET total_votes = total_votes + 1 WHERE id = '$candidateId'";
    if (mysqli_query($data, $update_sql)) {
        // Add the candidate ID to the session array
        $_SESSION['voted_candidates'][] = $candidateId;

        // Return the new vote count as JSON
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
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
<?php if (isset($_SESSION['username'])): ?>
<div class="wrapper">
    <div id="intro">
        <h1 class="call-out">Welcome to the HTS LRC Voting Portal!</h1>
        <p class="intro-p">
        We're excited to have you participate in this important event where you can make your voice heard. Our candidates have been working hard to represent your interests, and now it's your turn to choose who will lead. Voting is simple and secure. Please take a moment to review the candidate profiles and cast your vote thoughtfully. Your participation is crucial in shaping the future of our school.
        </p>
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
                <button type="submit" class="signup-btn" id="vote_button_<?php echo $row['id']; ?>" <?php echo in_array($row['id'], $_SESSION['voted_candidates']) ? 'disabled' : ''; ?>>
                    Vote Candidate
                </button>
            </form>
        </div>
    <?php endforeach; ?>
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
            alert(data.message);
            voteButton.disabled = false; // Re-enable button if voting failed
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating vote count');
        voteButton.disabled = false; // Re-enable button if there's an error
    });
}
</script>

<?php else: ?>
    <?php header("location: ./users-login.php", true, 302); exit(); ?>
<?php endif; ?>
</body>
</html>
