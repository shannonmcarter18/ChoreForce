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
    $child_id = $_POST["chore_assign"] ?? null;
    $description = $_POST["choredesc"] ?? null;
    $reward = $_POST["reward"] ?? null;

    // Optional: basic input validation
    if (!$child_id || !$description || !$reward) {
        echo "Please fill out all fields.";
        exit();
    }

    // Insert into database (assumes you have a `chore` table with these columns: id, child_id, description, reward, status)
    $sql = "INSERT INTO chore (child_id, description, reward, status)
            VALUES (?, ?, ?, 'incomplete')";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isd", $child_id, $description, $reward);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: chorelistmanage.html"); // Redirect after success
            exit();
        } else {
            echo "Error inserting chore.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare statement.";
    }

    mysqli_close($conn);
?>
