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

// --- Check if user is logged in ---
if (!isset($_SESSION["user_id"])) {
    header("Location: signin.html"); // Change 'signin.html' to your actual login page
    exit();
}

$parent_id = $_SESSION["user_id"];

// --- Fetch All Payment History for the Parent's Children ---
$all_payments = [];
$sql_fetch_all_payments = "
    SELECT
        p.PAYMENT_ID,
        u_child.FIRST_NAME AS child_name,
        p.PAYMENT_AMNT,
        p.PARENT_CARD_NUM,
        p.CHILD_CARD_NUM,
        p.PAYMENT_DATE
    FROM
        PAYMENT p
    JOIN
        CHILD c ON p.CHILD_ID = c.CID AND p.PARENT_ID = c.PID
    JOIN
        USER u_child ON c.ID = u_child.ID
    WHERE
        c.PID = ?
    ORDER BY
        p.PAYMENT_DATE DESC;
";

$stmt_fetch_all_payments = mysqli_prepare($conn, $sql_fetch_all_payments);

if ($stmt_fetch_all_payments) {
    mysqli_stmt_bind_param($stmt_fetch_all_payments, "i", $parent_id);
    mysqli_stmt_execute($stmt_fetch_all_payments);
    $result_all_payments = mysqli_stmt_get_result($stmt_fetch_all_payments);

    while ($row = mysqli_fetch_assoc($result_all_payments)) {
        $all_payments[] = $row;
    }
    mysqli_stmt_close($stmt_fetch_all_payments);
} else {
    echo "Error fetching all payment history.";
}

// --- Fetch Parent's Children for the New Payment Form ---
$children_list = [];
$sql_fetch_children = "
    SELECT
        u.FIRST_NAME,
        c.CID
    FROM
        CHILD c
    JOIN
        USER u ON c.ID = u.ID
    WHERE
        c.PID = ?
    ORDER BY
        u.FIRST_NAME;
";

$stmt_fetch_children = mysqli_prepare($conn, $sql_fetch_children);

if ($stmt_fetch_children) {
    mysqli_stmt_bind_param($stmt_fetch_children, "i", $parent_id);
    mysqli_stmt_execute($stmt_fetch_children);
    $result_children = mysqli_stmt_get_result($stmt_fetch_children);

    while ($row = mysqli_fetch_assoc($result_children)) {
        $children_list[] = $row;
    }
    mysqli_stmt_close($stmt_fetch_children);
} else {
    echo "Error fetching children list for new payment form.";
}

// --- Handle New Payment Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_payment'])) {
    $child_id = $_POST['child_id'];
    $payment_amount = $_POST['payment_amount'];
    $parent_card = $_POST['parent_card'];
    $child_card = $_POST['child_card'];
    $payment_date = date("Y-m-d H:i:s"); // Current timestamp

    $sql_insert_payment = "
        INSERT INTO PAYMENT (PARENT_ID, CHILD_ID, PAYMENT_AMNT, PARENT_CARD_NUM, CHILD_CARD_NUM, PAYMENT_DATE)
        VALUES (?, ?, ?, ?, ?, ?);
    ";

    $stmt_insert_payment = mysqli_prepare($conn, $sql_insert_payment);

    if ($stmt_insert_payment) {
        mysqli_stmt_bind_param($stmt_insert_payment, "iiisss", $parent_id, $child_id, $payment_amount, $parent_card, $child_card, $payment_date);
        if (mysqli_stmt_execute($stmt_insert_payment)) {
            // Payment added successfully, redirect to refresh the page
            header("Location: moneymanage.php");
            exit();
        } else {
            echo "Error adding new payment: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt_insert_payment);
    } else {
        echo "Error preparing insert payment statement.";
    }
}

// --- Close DB connection ---
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Payment History - ChoreForce</title>
    <style>
        body {
            background-color: #CCE5FF;
            font-family: sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-items a {
            color: #003366;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .nav-items a:hover {
            background-color: #e0f0ff;
        }

        .nav-items {
            display: flex;
            gap: 15px;
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #003366;
        }

        h2 {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            color: #003366;
            margin-top: 50px;
        }

        h3 {
            font-size: 24px;
            font-weight: bold;
            color: #003366;
            margin-top: 40px;
            text-align: center;
        }

        table {
            background-color: white;
            color: #333;
            width: 85%;
            margin: 20px auto;
            border-radius: 8px;
            font-size: 16px;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        table th {
            background-color: #eaf2ff;
            font-weight: bold;
            color: #003366;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        form {
            width: 50%;
            margin: 30px auto 80px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #4967ad;
            font-size: 18px;
        }

        input[type="text"], select {
            width: 95%;
            padding: 10px;
            margin-top: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .button {
            background-color: #4967ad;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 18px;
            margin-top: 25px;
            cursor: pointer;
            border: none;
            width: 100%;
        }

        .button:hover {
            background-color: #3a508a;
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

    <h2>Payment History</h2>

    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Child Name</th>
                <th>Amount ($)</th>
                <th>Parent Card</th>
                <th>Child Card</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($all_payments)): ?>
                <tr><td colspan="6">No payment history available.</td></tr>
            <?php else: ?>
                <?php foreach ($all_payments as $payment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($payment['PAYMENT_ID']); ?></td>
                        <td><?php echo htmlspecialchars($payment['child_name']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($payment['PAYMENT_AMNT'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($payment['PARENT_CARD_NUM']); ?></td>
                        <td><?php echo htmlspecialchars($payment['CHILD_CARD_NUM']); ?></td>
                        <td><?php echo htmlspecialchars($payment['PAYMENT_DATE']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Add New Payment</h3>
    <form method="post" action="">
        <input type="hidden" name="add_payment" value="true">

        <label for="child_id">Child Name:</label>
        <select id="child_id" name="child_id" required>
            <option value="">Select Child</option>
            <?php foreach ($children_list as $child): ?>
                <option value="<?php echo htmlspecialchars($child['CID']); ?>">
                    <?php echo htmlspecialchars($child['FIRST_NAME']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="payment_amount">Amount to Send ($):</label>
        <input type="text" id="payment_amount" name="payment_amount" pattern="[0-9]+(\.[0-9]{1,2})?" title="Enter a numeric value (e.g., 10 or 10.50)" required>

        <label for="parent_card">Parent's Card Number:</label>
        <input type="text" id="parent_card" name="parent_card" pattern="[0-9]{12,16}" title="Enter a valid card number (12-16 digits)" required>

        <label for="child_card">Child's Card Number:</label>
        <input type="text" id="child_card" name="child_card" pattern="[0-9]{12,16}" title="Enter a valid card number (12-16 digits)" required>

        <button type="submit" class="button">Add Payment</button>
    </form>

</body>
</html>
