<?php
session_start();

$parent_id = $_SESSION['user_id'] ?? null;

if (!$parent_id) {
    echo $parent_id; 
    echo "No user session found.";
    //echo "<script>console.log('No user session found.');</script>";
    exit();
}

echo $parent_id; 
echo "Hello this is shannon"; 

$db_host = "localhost";
$db_user = "root";
$db_pswd = "";
$db_name = "choreforce";

$conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT id, name FROM child WHERE parent_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $parent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    echo '<input type="radio" id="child_' . $row['id'] . '" name="chore_assign" value="' . $row['id'] . '" required>';
    echo '<label for="child_' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</label><br>';
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
