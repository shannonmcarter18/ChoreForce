<?php
session_start();

// --- Database Connection ---
$db_host = "localhost";
$db_user = "root";
$db_pswd = "";
$db_name = "choreforce"; 
$conn = "";

try {
    $conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . mysqli_connect_error());
}

// --- Check if user is logged in ---
$user_id = $_SESSION["user_id"]; // This is the CHILD's ID
$user_first_name = "Child"; 

// --- Fetch Child's Information ---
$sql_fetch_user = "SELECT FIRST_NAME FROM USER WHERE ID = ?";
$stmt_fetch_user = mysqli_prepare($conn, $sql_fetch_user);

if ($stmt_fetch_user) {
    mysqli_stmt_bind_param($stmt_fetch_user, "i", $user_id);
    mysqli_stmt_execute($stmt_fetch_user);
    mysqli_stmt_bind_result($stmt_fetch_user, $fetched_first_name);
    if (mysqli_stmt_fetch($stmt_fetch_user)) {
        $user_first_name = $fetched_first_name;
        $_SESSION["first_name"] = $user_first_name;
    }
    mysqli_stmt_close($stmt_fetch_user);
} else {
    echo "Error fetching user data.";
}

// --- Fetch Child's CID (from CHILD table) ---
$child_cid = null;
$sql_fetch_cid = "SELECT CID FROM CHILD WHERE ID = ?";
$stmt_fetch_cid = mysqli_prepare($conn, $sql_fetch_cid);

if ($stmt_fetch_cid) {
    mysqli_stmt_bind_param($stmt_fetch_cid, "i", $user_id);
    mysqli_stmt_execute($stmt_fetch_cid);
    mysqli_stmt_bind_result($stmt_fetch_cid, $fetched_cid);
    if (mysqli_stmt_fetch($stmt_fetch_cid)) {
        $child_cid = $fetched_cid;
    }
    mysqli_stmt_close($stmt_fetch_cid);
}

// --- Handle checkbox submission ---
$success_message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['completed_chores'])) {
    foreach ($_POST['completed_chores'] as $chore_id) {
        $sql_update_status = "UPDATE CHORE SET STATUS = 'Completed' WHERE CHORE_ID = ? AND CHILD_ID = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update_status);
        if ($stmt_update) {
            mysqli_stmt_bind_param($stmt_update, "ii", $chore_id, $child_cid);
            mysqli_stmt_execute($stmt_update);
            mysqli_stmt_close($stmt_update);
        }
    }
    $success_message = "Selected chores marked as completed!";
}

// --- Fetch Child's Assigned Chores ---
$child_chores = [];
$chore_fetch_error = false;

$sql_fetch_chores = "
    SELECT
        ac.CHORE_ID,
        ac.DESCRIPTION AS chore_description,
        ac.STATUS
    FROM
        CHILD cl
    JOIN
        CHORE ac ON cl.CID = ac.CHILD_ID AND cl.PID = ac.PARENT_ID
    WHERE
        cl.ID = ?;
";

$stmt_fetch_chores = mysqli_prepare($conn, $sql_fetch_chores);

if ($stmt_fetch_chores) {
    mysqli_stmt_bind_param($stmt_fetch_chores, "i", $user_id);
    mysqli_stmt_execute($stmt_fetch_chores);
    $result = mysqli_stmt_get_result($stmt_fetch_chores);

    while ($row = mysqli_fetch_assoc($result)) {
        $child_chores[] = $row;
    }
    mysqli_stmt_close($stmt_fetch_chores);
} else {
    $chore_fetch_error = true;
    echo "Error preparing chore fetch statement: " . mysqli_error($conn);
}

// --- Close DB connection ---
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Child Portal - <?php echo htmlspecialchars($user_first_name); ?></title>
    <style>
        body { background-color: #CCE5FF; font-family: sans-serif; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav-items a { color: #003366; text-decoration: none; padding: 8px 12px; border-radius: 4px; transition: background-color 0.2s ease; }
        .nav-items a:hover { background-color: #e0f0ff; }
        .nav-items { display: flex; gap: 15px; }
        .logo { font-size: 32px; font-weight: bold; color: #003366; }
        h2 { font-size: 32px; font-weight: bold; margin-top: 60px; text-align: center; color: #003366; }
        p { color: #4967ad; font-size: 18px; font-weight: normal; text-align: center; margin-top: -10px; margin-bottom: 40px; }
        .button { background-color: #4967ad; color: white; padding: 12px 22px; border-radius: 5px; border: none; text-align: center; font-weight: bold; font-size: 16px; cursor: pointer; transition: background-color 0.2s ease; margin: 20px auto; display: block; }
        .button:hover { background-color: #3a508a; }
        h3 { font-size: 24px; font-weight: bold; color: #003366; margin-left: 7.5%; margin-bottom: 15px; margin-top: 40px; }
        table { background-color: white; color: #333; width: 85%; margin-left: auto; margin-right: auto; margin-bottom: 50px; border-radius: 8px; font-size: 16px; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #eaf2ff; font-weight: bold; color: #003366; }
        tr:last-child td { border-bottom: none; }
        tr:hover { background-color: #f9f9f9; }
        td.status-pending { color: #ffc107; font-weight: bold; }
        td.status-completed { color: #28a745; font-weight: bold; }
        .no-chores-message td { text-align: center; color: #777; font-style: italic; padding: 20px; }
        .success-message { text-align: center; color: green; margin-top: 20px; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

<nav>
    <div class="navbar">
        <div class="logo">ChoreForce</div>
        <div class="nav-items">
            <a href="childportal.php">My Portal</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<h2>Welcome, <?php echo htmlspecialchars($user_first_name); ?></h2>
<p>Username: <strong><?php echo htmlspecialchars($user_id); ?></strong></p>

<h3>My Chores:</h3>

<?php if (!empty($success_message)): ?>
    <div class="success-message"> <?php echo htmlspecialchars($success_message); ?> </div>
<?php endif; ?>

<form method="POST" action="childportal.php">
<table>
    <thead>
        <tr>
            <th style="width: 10%">Done?</th>
            <th style="width: 15%">Chore ID</th>
            <th style="width: 55%">Chore Description</th>
            <th style="width: 20%">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($chore_fetch_error): ?>
            <tr class="no-chores-message">
                <td colspan="4">Could not load chore data due to a database error.</td>
            </tr>
        <?php elseif (empty($child_chores)): ?>
            <tr class="no-chores-message">
                <td colspan="4">No chores assigned to you yet.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($child_chores as $chore): ?>
                <tr>
                    <td>
                        <?php if (strtolower($chore['STATUS']) == 'pending'): ?>
                            <input type="checkbox" name="completed_chores[]" value="<?php echo htmlspecialchars($chore['CHORE_ID']); ?>">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($chore['CHORE_ID']); ?></td>
                    <td><?php echo htmlspecialchars($chore['chore_description']); ?></td>
                    <td class="status-<?php echo strtolower(htmlspecialchars($chore['STATUS'])); ?>">
                        <?php echo htmlspecialchars($chore['STATUS']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if (!empty($child_chores)): ?>
    <button type="submit" class="button">Mark Selected as Completed</button>
<?php endif; ?>
</form>

</body>
</html>