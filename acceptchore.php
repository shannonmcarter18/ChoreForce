<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "choreforce");

    if (!$conn) {
        header("Location: acceptchore.html?error=db");
        exit();
    }

    $chore_id = $_POST["choreid"];
    $decision = $_POST["chore_accept"]; // "yes" or "no"

    // Update chore status based on decision
    $status = ($decision === "yes") ? "accepted" : "denied";

    $sql = "UPDATE chore SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $chore_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: acceptchore.html?success=updated");
    } else {
        header("Location: acceptchore.html?error=update");
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
?>
