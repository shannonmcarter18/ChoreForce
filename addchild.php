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

// Get current parent ID
$parent_id = $_SESSION['user_id'];

// Fetch children linked to this parent
$child_query = $conn->prepare("
    SELECT u.FIRST_NAME, u.LAST_NAME
    FROM Child c
    JOIN User u ON c.id = u.ID
    WHERE c.pid = ?
    ORDER BY c.cid ASC
");
$child_query->bind_param("i", $parent_id);
$child_query->execute();
$child_result = $child_query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* your CSS from earlier */
        body {
            background-color: #CCE5FF;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: white;
        }
        .nav-items a {
            color: #003366;
            text-decoration: none;
        }
        .nav-items {
            display: flex;
            gap: 20px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #003366;
        }
        h2 {
            font-size: 32px;
            color: #003366;
            margin-top: 100px;
            text-align: center;
        }
        label, p {
            font-size: 24px;
            font-weight: bold;
            color: #4967ad;
            margin-left: 30px;
        }
        input, textarea {
            margin-top: 10px;
            margin-left: 30px;
            font-size: 24px;
        }
        .addchild-btn {
            width: 25%;
            padding: 12px;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 15px;
            background: #4967ad;
            color: white;
        }
        table {
            margin-left: 30px;
            margin-top: 20px;
            border-collapse: collapse;
        }
        td {
            padding: 8px 16px;
            font-size: 20px;
            color: #003366;
        }
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

    <h2>Add New Child</h2>

    <form action="process_addchild.php" method="post">
        <br><br>
        <label for="child_name">Child's name:</label><br>
        <input type="text" id="child_name" name="child_name"
               pattern="[A-Za-z ]*" title="letters and spaces only"
               placeholder="Enter the child's full name" /><br><br><br>
        <p style="text-align: center">
            <button type="submit" class="addchild-btn">Add Child</button>
        </p>
    </form>

    <label for="curr_child_list">Current child list:</label><br>

    <table>
        <?php if ($child_result->num_rows > 0): ?>
            <?php while ($row = $child_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['FIRST_NAME'] . ' ' . $row['LAST_NAME']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td>No children added yet.</td>
            </tr>
        <?php endif; ?>
    </table>

    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.has("success")) alert("Child added successfully!");
        if (params.has("error")) alert("Failed to add child. Try again.");
    </script>
</body>
</html>

<?php
$child_query->close();
$conn->close();
?>
