<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	session_start();

    $db_host = "localhost";
	$db_user = "root";
	$db_pswd = "";
	$db_name = "choreforce";
	$conn = "";

    try{
		$conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);
	}
	catch(mysqli_sql_exception $e){
		echo "unable to connect";
		exit();
	}

	# gather all inputs from html file. what's in the quotes should be the name of the input in the html file.
	$child_name = $_POST["childname"]?? '';
    $child_id = $_POST["childid"] ?? '';
	$parent_id = $_SESSION["user_id"];

	#input validation
	if (!$child_name || !$child_id ) {
		echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
		exit();
	}
	if (!$parent_id) {
		echo "<script>alert('Session expired or user not logged in.'); window.location.href = 'login.php';</script>";
		exit();
	}

	#update the child user's name and that the child is confirmed to be the parent's child from the parentid in the session
    $sql = "UPDATE USER u
			JOIN CHILD c ON u.ID = c.ID 
			JOIN PARENT p ON c.PID = p.ID
			SET u.FIRST_NAME = ? 
			WHERE c.CID = ? AND p.ID = ?";

	# make $sql from a string into a real statement to ge executed and added to db
	$stmt = mysqli_prepare($conn, $sql);
	if($stmt) {
		echo "$child_name, $child_id, $parent_id";
	} else {
		echo "statement not run";
	}
	if(mysqli_stmt_bind_param($stmt, "sii", $child_name, $child_id, $parent_id)){
		echo "statement binded";
	} else {
		echo "error";
	}
	if(mysqli_stmt_execute($stmt)){
		echo "<script>alert('Child named changed.'); window.location.href = 'childmanage.html';</script>";
	} else {
		echo "<script>alert('Error with processing child name change.'); window.history.back();</script>";
	}
	mysqli_stmt_close($stmt);
	
	$conn->close();

?>