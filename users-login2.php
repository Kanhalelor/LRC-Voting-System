<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "hts_db";

session_start();

echo '<pre>';
print_r($_SESSION); // Print session variables for debugging
echo '</pre>';

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection error");
}

// Global variable for message
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare the SQL query to prevent SQL injection
    $sql = "SELECT * FROM candidates WHERE username='" . mysqli_real_escape_string($data, $username) . "' 
            AND password='" . mysqli_real_escape_string($data, $password) . "'";

    $result = mysqli_query($data, $sql);

    // Check if the query executed successfully
    if (!$result) {
        die("Query failed: " . mysqli_error($data));
    }

    $row = mysqli_fetch_array($result);

    // Debugging: Output the fetched row for verification
    if ($row) {
        echo "<pre>"; // For better readability
        print_r($row);
        echo "</pre>";

        if ($row["usertype"] == "user") {
            $_SESSION["username"] = $row["username"];
            $_SESSION["user_id"] = $row["id"]; // Store user ID in session
            
            error_log("User ID: " . $_SESSION['user_id']);
            
            // Debugging: Check if user_id is set
            if (isset($_SESSION["user_id"])) {
                header("location: ./client/test4.php"); // Redirect to the voting page
                exit();
            } else {
                echo "<script>alert('User ID not set in session. Please log in again.');</script>";
            }
        } elseif ($row["usertype"] == "admin") {
            $_SESSION["username"] = $row["username"];
            $_SESSION["user_id"] = $row["id"]; // Store user ID in session
            
            header("location: ./server/admin-home.php"); // Redirect to admin dashboard
            exit();
        }
    } else {
        // Invalid username or password
        echo "<script>alert('Username or password incorrect');</script>";
        header('location: ./users-login.php');
        $msg = "Username or password incorrect";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | HTS LRC Website</title>
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
        <form method="POST" class="login" action="./users-login2.php">
            <h2 class="form-header">LOGIN</h2>
            <p style="color: tomato;"><?php echo $msg; ?></p>
            <label for="username">Username</label>
            <input type="text" name="username" id="mail" required>
            <label for="Password">Password</label>
            <input type="password" name="password" id="key" required onblur="validatePassword(this.value)" autocomplete="current-password">
            <button class="login-btn" type="submit">LOGIN</button>
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
