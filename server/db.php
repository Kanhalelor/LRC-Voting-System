<?php
$host = "localhost";
$dbname = "hts_DB";
$username = "root";
$password = "Bee&Gee";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("An error occured. please try agin later: " . $mysqli->connect_error);
}

return $mysqli;