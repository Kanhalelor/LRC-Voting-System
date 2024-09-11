<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LRC-Voting System Home Page</title>
    <!-- <link rel="stylesheet" href="./assests/css/main.css"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nerko+One&display=swap" rel="stylesheet">
</head>
<style>

    html {
        scroll-behavior: smooth;
    }
    body {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        width: 100vw;
        height: 100vh;
        background-image: url(../client/assests/images/background-img.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        padding: 0;
    }
    .nerko-one-regular {
        font-family: "Nerko One", cursive;
        font-weight: 400;
        font-style: normal;
    }

    .header {
        width: 100vw;
        height: 30vh;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: .8rem 0;
        background: #eedb06;
        border-bottom: 2px solid #121212;
        margin: 0;
    }
    .header .title {
        font-family: "Nerko One", cursive;
        font-weight: 800;
        width: 100%;
        font-size: 60px;
    }
    .sub-nav {
        margin: 50px;
        width: 100%;
        height: 50%;
        padding: 50px 0;


        display: flex;
        align-content: center;
        justify-content: center;


    }
    .sub-nav a {
        background: #345;
        text-align: center;
        color: #eee;
        text-decoration: none;
        height: min-content;
        font-family: "Nerko One", cursive;


        margin: 0 .4rem;
        padding: 1.2rem 1.3rem;

        border-radius: 5px;
    }
    .sub-nav a:hover {
        color: #345;
        background: #eee;
    }
    .candidates {
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: .3rem ;
        background: #2332;
        transition: .3s linear;
    }

    footer {
        width: 100%;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: .3rem;

        font-size: large;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
</style>
<body>
    <div class="header">
        <h1 class="title">
            LRC-Voting Web Application
        </h1>
    </div>
    <div class="sub-nav">
        <a href="../users-login.php" target="_blank">Admin Login</a>
        <a href="./how-to-vote.html" target="_blank">How to Vote.</a>
        <a href="./privacy-policy.html" target="_blank">Data privacy</a>
        <a href="./results.php" target="_blank">Vote</a>
        <a href="https://github.com/liliana-bee/LRC-Voting-System" target="_blank">View on GitHub</a>
    </div>
    <footer>
        Copyright &copy 2024
    </footer>
</body>
</html>