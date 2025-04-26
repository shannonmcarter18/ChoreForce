<?php
    session_start();
   
    $db_host = "localhost";
	$db_user = "root";
	$db_pswd = "";
	$db_name = "choreforce";
	$conn = "";

	$parent_id = $_SESSION['user_id'] ?? null;
	$child_id = $_POST["chore_assign"] ?? null;
	$description = $_POST["choredesc"] ?? null;
	$reward = $_POST["reward"] ?? null;

	$conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);

    if(!$conn){
		echo "Connection failed.";
		exit();
	}

	$sql = "UPDATE chore SET 

	

    
?>