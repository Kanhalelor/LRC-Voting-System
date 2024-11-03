<?php

session_start();

if(!isset($_GET["id"]) && !isset($_SESSION["username"]) == "admin") {
    header("location: ./admin-home.php");
    exit;
} else {
    $host="localhost";
    $user="root";
    $password="";
    $db="hts_db";

    $data = mysqli_connect($host,$user,$password,$db);

    $id = $_GET["id"];

    // Retrieve candidate data
    $sql = "SELECT * FROM candidates WHERE id = ?";
    $stmt = mysqli_prepare($data, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $candidateData = mysqli_fetch_assoc($result);

    // Insert data into archived_candidates table, including profileIMG
    $sql = "INSERT INTO archived_candidates (username, password, usertype, fullNames, profileIMG) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($data, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $candidateData['username'], $candidateData['password'], $candidateData['usertype'], $candidateData['fullNames'], $candidateData['profileIMG']);
    mysqli_stmt_execute($stmt);

    // Delete candidate from candidates table
    $sql = "DELETE FROM candidates WHERE id = ?";
    $stmt = mysqli_prepare($data, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("location: ./admin-home.php");

?>