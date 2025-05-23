<?php
session_start();

// --- Database Connection ---
$db_host = "localhost";
$db_user = "root";
$db_pswd = "";
$db_name = "choreforce"; // Your database name
$conn = "";

try {
    $conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION["user_id"]; // This is the PARENT's ID from the user table
$user_first_name = "Parent"; // Default values

$choreid = $_POST['choreid'] ?? '';
$accept = $_POST['accept'] ?? '';

if ($choreid && $accept === 'yes') {
    $stmt = mysqli_prepare($conn, "DELETE FROM CHORE WHERE CHORE_ID = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $choreid);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Chore deleted successfully!'); window.location.href='parentportal.php';</script>";
        } else {
            echo "<script>alert('No chore found with that ID.'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Chore deletion not confirmed.'); window.history.back();</script>";
}

$conn->close();
?>