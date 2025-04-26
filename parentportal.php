<?php
// Start the session to access logged-in user data
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

// --- Check if user is logged in ---
//if (!isset($_SESSION["user_id"])) {
    // Redirect to login page if not logged in
    //header("Location: signin.html"); // Change 'login.html' to your actual login page
    //exit();
//}

$user_id = $_SESSION["user_id"]; // This is the PARENT's ID from the user table
$user_first_name = "Parent"; // Default values


// --- Fetch Parent's Information ---
// Using exact column names from your 'user' table: ID, FIRST_NAME, EMAIL
$sql_fetch_user = "SELECT FIRST_NAME FROM USER WHERE ID = ?";
$stmt_fetch_user = mysqli_prepare($conn, $sql_fetch_user);

if ($stmt_fetch_user) {
    mysqli_stmt_bind_param($stmt_fetch_user, "i", $user_id);
    mysqli_stmt_execute($stmt_fetch_user);
    mysqli_stmt_bind_result($stmt_fetch_user, $fetched_first_name);
    if (mysqli_stmt_fetch($stmt_fetch_user)) {
        $user_first_name = $fetched_first_name;
        $_SESSION["first_name"] = $user_first_name; // Update session if needed
    } else {
        // User ID from session not found
    }
    mysqli_stmt_close($stmt_fetch_user);
} else {
    // Error preparing statement
    echo "Error fetching user data.";
}

// --- Fetch Children's Chore Data (Using PROVIDED TABLES) ---
$children_chores = []; // Initialize an empty array
$chore_fetch_error = false; // Flag for errors

// Query using 'child_link', 'assigned_chores', and 'user' tables
// Matching parent ID in child_link (cl.PID) and assigned_chores (ac.PARENT_ID)
// Matching child ID in child_link (cl.ID) and assigned_chores (ac.CHILD_ID)
// Getting child name from user table (u.ID = cl.ID)
$sql_fetch_chores = "
    SELECT
        u.FIRST_NAME AS child_name,      -- Child's first name from user table
        ac.CHORE_ID,                     -- Assigned chore's specific ID
        ac.DESCRIPTION AS chore_description, -- The description of the assigned chore
        ac.STATUS                        -- The status of the assigned chore
    FROM
        CHILD cl
    JOIN
        USER u ON cl.ID = u.ID       
    JOIN
        CHORE ac ON cl.CID = ac.CHILD_ID AND cl.PID = ac.PARENT_ID
    WHERE  
        cl.PID = ?;                     
";

$stmt_fetch_chores = mysqli_prepare($conn, $sql_fetch_chores);

if ($stmt_fetch_chores) {
    mysqli_stmt_bind_param($stmt_fetch_chores, "i", $user_id); // Bind the logged-in parent's user ID
    mysqli_stmt_execute($stmt_fetch_chores);
    $result = mysqli_stmt_get_result($stmt_fetch_chores);

    while ($row = mysqli_fetch_assoc($result)) {
        $children_chores[] = $row; // Add each row (chore) to the array
    }
    mysqli_stmt_close($stmt_fetch_chores);
} else {
    // Query preparation failed. Could be syntax error or table/column name mismatch.
    $chore_fetch_error = true; // Flag that fetching failed
    echo "Error preparing chore fetch statement: " . mysqli_error($conn);
}

// --- Close DB connection ---
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Parent Portal - <?php echo htmlspecialchars($user_first_name); ?></title>
    <style>
        /* --- [Your CSS Styles Here - Same as previous example] --- */
        body { background-color: #CCE5FF; font-family: sans-serif; }
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .nav-items a { color: #003366; text-decoration: none; padding: 8px 12px; border-radius: 4px; transition: background-color 0.2s ease; }
        .nav-items a:hover { background-color: #e0f0ff; }
        .nav-items { display: flex; gap: 15px; }
        .logo { font-size: 32px; font-weight: bold; color: #003366; }
        h2.welcome-banner { font-size: 32px; font-weight: bold; margin-top: 60px; text-align: center; color: #003366; }
        p.user-info { color: #4967ad; font-size: 18px; font-weight: normal; text-align: center; margin-top: -10px; margin-bottom: 40px; }
        p.user-info strong { font-weight: bold; color: #003366; }
        .button-container { text-align: center; margin-bottom: 60px; display: flex; justify-content: center; flex-wrap: wrap; gap: 20px; }
        .button { background-color: #4967ad; color: white; padding: 12px 22px; border-radius: 5px; border: none; text-align: center; font-weight: bold; text-decoration: none; display: inline-block; font-size: 16px; cursor: pointer; transition: background-color 0.2s ease; }
        .button:hover { background-color: #3a508a; }
        h3.section-heading { font-size: 24px; font-weight: bold; color: #003366; margin-left: 7.5%; margin-bottom: 15px; margin-top: 0; }
        table.chore-table { background-color: white; color: #333; width: 85%; margin-left: auto; margin-right: auto; margin-bottom: 50px; border-radius: 8px; font-size: 16px; border-collapse: collapse; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .chore-table th, .chore-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        .chore-table th { background-color: #eaf2ff; font-weight: bold; color: #003366; }
        .chore-table tr:last-child td { border-bottom: none; }
        .chore-table tr:hover { background-color: #f9f9f9; }
        /* Status Styling Classes (use lowercase status from DB) */
        .chore-table td.status-assigned { color: #6c757d; } /* Bootstrap secondary text color */
        .chore-table td.status-pending { color: #ffc107; font-weight: bold; } /* Bootstrap warning color */
        .chore-table td.status-completed { color: #28a745; font-weight: bold; } /* Bootstrap success color */
        .chore-table td.status-approved { color: #007bff; font-weight: bold;} /* Bootstrap primary color */
        .chore-table td.status-rejected { color: #dc3545; font-weight: bold;} /* Bootstrap danger color */
        .no-chores-message td { text-align: center; color: #777; font-style: italic; padding: 20px; }
    </style>
</head>
<body>
    <nav>
        <div class="navbar">
            <div class="logo">ChoreForce</div>
            <div class="nav-items">
                <a href="parentportal.php">My Portal</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <h2 class="welcome-banner">Welcome, <?php echo htmlspecialchars($user_first_name); ?></h2>
    <p class="user-info">Username: <strong><?php echo htmlspecialchars($user_id); ?></strong></p>

    <div class="button-container">
         <a class="button" href="chorelistmanage.html">Manage Chorelist</a>
        <a class="button" href="childmanage.html">Manage Children</a>
        <a class="button" href="moneymanage.php">Manage Payments</a>
        <a class="button" href="acceptchore.php">Approve Completions</a>
    </div>

    <h3 class="section-heading">Children's Chores Overview:</h3>

    <table class="chore-table">
        <thead>
            <tr>
                <th style="width: 25%">Kid</th>
                <th style="width: 15%">ChoreID</th> <th style="width: 35%">Chore Description</th> <th style="width: 25%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($chore_fetch_error): ?>
                <tr class="no-chores-message">
                    <td colspan="4">Could not load chore data due to a database error.</td>
                </tr>
            <?php elseif (empty($children_chores)): ?>
                <tr class="no-chores-message">
                    <td colspan="4">No chores found assigned to your children.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($children_chores as $chore): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($chore['child_name']); ?></td>
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

</body>
</html>