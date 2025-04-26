<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "choreforce");

    if (!$conn) {
        header("Location: addchild.html?error=db");
        exit();
    }

    $child_fullname = trim($_POST["child_name"]);

    // Break full name into first and last names (if both are present)
    $parts = explode(" ", $child_fullname, 2);
    $first = $parts[0];
    $last = isset($parts[1]) ? $parts[1] : "";

    $parent_id = $_SESSION['user_id'];

    $sql = "INSERT INTO user (firstname, lastname, is_child, parent_id)
            VALUES (?, ?, 1, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $first, $last, $parent_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: addchild.html?success=added");
    } else {
        header("Location: addchild.html?error=insert");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
?>
