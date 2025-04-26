<?php
session_start();

$parent_id = $_SESSION['user_id'] ?? null;

if (!$parent_id) {
    echo "No user session found.";
    exit();
}

$db_host = "localhost";
$db_user = "root";
$db_pswd = "";
$db_name = "choreforce";

$conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT child.CID, user.FIRST_NAME, user.LAST_NAME
        FROM CHILD
        JOIN USER ON child.ID = user.ID
        WHERE child.PID = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("SQL error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "i", $parent_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $full_name = htmlspecialchars($row['FIRST_NAME'] . ' ' . $row['LAST_NAME']);
    echo '<input type="radio" id="child_' . $row['CID'] . '" name="chore_assign" value="' . $row['CID'] . '" required>';
    echo '<label for="child_' . $row['CID'] . '">' . $full_name . '</label><br>';
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
