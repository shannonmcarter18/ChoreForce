<?php

$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$choreid = $_POST['choreid'] ?? '';
$accept = $_POST['accept'] ?? '';

if ($choreid && $accept === 'yes') {
    $stmt = $conn->prepare("DELETE FROM chores WHERE id = ?");
    $stmt->bind_param("i", $choreid);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "<script>alert('Chore deleted successfully!'); window.location.href='parentportal.html';</script>";
    } else {
        echo "<script>alert('No chore found with that ID.'); window.history.back();</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Chore deletion not confirmed.'); window.history.back();</script>";
}

$conn->close();
?>
