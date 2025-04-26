<?php
	session_start();

	$parent_id

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
    $child_id = $_POST["childid"];
    $child_name = $_POST["childname"];

	#update the child user's name and that the child is confirmed to be the parent's child

    $sql = "UPDATE USER 
			 SET FIRST_NAME = ?
			 WHERE id = ?";

	# make $sql1 from a string into a real statement
	$stmt1 = mysqli_prepare($conn, $sql);

	if(mysqli_query($conn, $sql)) {
		echo "Recorded update"
	} else {
		echo "Error updating record: " . mysqli_error($conn);
	}
	
	mysqli_close($conn);

	$result = mysqli_stmt_get_result($sql);

	$r = mysqli_fetch_assoc($result);


?>