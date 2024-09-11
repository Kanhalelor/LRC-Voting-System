<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voted successfully</title>
</head>
<style>
        /* html scroll behaviour */
        html {
      scroll-behavior: smooth;
    }
    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    /* body */
    body {
      font-family: 'Source Sans Pro', sans-serif;
      width: 100%;
      height: 100%;
      background: #e0e0e0;

      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      width: 100vw;
      height: 100vh;
    }
    .success {
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 36px;
        font-weight: 800;
    }
    .next-voter {
        margin: 1rem 0;
        width: 100%;
    }
a {
  display: grid;
  place-items: center;
  margin: auto;
  text-decoration: none;
  background: orange;
  color: #fff;
  font-size: 16px;
  padding: 0.6rem 0.2rem;
  cursor: pointer;
  width: 100px;
  text-align: center;
}

a:hover {
  background: #fff;
  color: #121212;
  transition: .5s ease-in-out;
  transform: translateY(-10px) scale(1.02);
  border-bottom: 2px solid #f8851a;
}
</style>
<body>
<div id="success">
        <h1 class="call-out">Your vote has been cast successfully!</h1>
    </div>
    <div class="next-voter">
        <a href="./results.php">Next Voter</a>
    </div>
</body>
</html>