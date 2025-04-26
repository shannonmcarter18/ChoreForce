<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "choreforce";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate form input
if (!isset($_POST['child_name']) || trim($_POST['child_name']) === '') {
    header("Location: addchild.php?error=1");
    exit();
}

// Split child_name into first and last name
$full_name = trim($_POST['child_name']);
$name_parts = explode(' ', $full_name, 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

// Get current parent ID
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$parent_id = $_SESSION['user_id'];

// Insert child into User table first
$insert_user_query = $conn->prepare("
    INSERT INTO User (FIRST_NAME, LAST_NAME, EMAIL, PASSWORD)
    VALUES (?, ?, '', '')
");
$insert_user_query->bind_param("ss", $first_name, $last_name);
if (!$insert_user_query->execute()) {
    header("Location: addchild.php?error=1");
    exit();
}

// Get the newly created child ID
$child_id = $insert_user_query->insert_id;

// Get current highest cid for this parent
$cid_query = $conn->prepare("SELECT MAX(cid) AS max_cid FROM Child WHERE pid = ?");
$cid_query->bind_param("i", $parent_id);
$cid_query->execute();
$cid_result = $cid_query->get_result();
$cid_row = $cid_result->fetch_assoc();
$new_cid = ($cid_row['max_cid'] !== null) ? $cid_row['max_cid'] + 1 : 1;

// Insert into Child table
$insert_child_query = $conn->prepare("
    INSERT INTO Child (pid, id, cid)
    VALUES (?, ?, ?)
");
$insert_child_query->bind_param("iii", $parent_id, $child_id, $new_cid);
if (!$insert_child_query->execute()) {
    header("Location: addchild.php?error=1");
    exit();
}

// Success
header("Location: addchild.php?success=1");
exit();

?>
