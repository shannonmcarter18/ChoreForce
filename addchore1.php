<?php
session_start();

$db_host = "localhost";
$db_user = "root";
$db_pswd = "";
$db_name = "choreforce";

$conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$parent_id = $_SESSION['user_id'] ?? null; // Get parent ID from session
$child_id = $_POST["chore_assign"] ?? null;
$description = $_POST["choredesc"] ?? null;
$reward = $_POST["reward"] ?? null;

// Basic input validation
if (!$parent_id || !$child_id || !$description || !$reward) {
    echo "Please fill out all fields.";
    exit();
}

// Insert into database
$sql = "INSERT INTO chore (parent_id, child_id, description, reward_amnt, status)
        VALUES (?, ?, ?, ?, 'incomplete')";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iisd", $parent_id, $child_id, $description, $reward);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: chorelistmanage.html"); // Redirect after success
        exit();
    } else {
        echo "Error inserting chore: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Failed to prepare statement: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
