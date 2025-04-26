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

$user_id = $_SESSION["user_id"]; // PARENT's ID
$user_first_name = "Parent"; // Default values

$childid = $_POST['childid'] ?? '';
$accept = $_POST['accept'] ?? '';

if ($childid && $accept === 'yes') {
    $stmt = mysqli_prepare($conn, "DELETE FROM CHILD WHERE CID= ? AND PID= ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $childid, $user_id);

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>alert('Child deleted successfully!'); window.location.href='parentportal.php';</script>";
        } else {
            echo "<script>alert('No child found with that ID.'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Child deletion not confirmed.'); window.history.back();</script>";
}

$conn->close();
?>
