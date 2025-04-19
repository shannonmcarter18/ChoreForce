<?php
	# i think this is needed in order for page redirects to remember the instance of user
	session_start();

	# try to connect to server. $db_name depends on whatever you decide to name the database on xammp. the rest should be the same
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
	}

	# gather all inputs from html file. what's in the quotes should be the name of the input in the html file.
	$first_name = $_POST["fname"];
	$last_name = $_POST["lname"];
	$email = $_POST["email"];
	$password = $_POST["password"];

	$sql1 = "INSERT INTO user(first_name, last_name, email, password)
			VALUES (?, ?, ?, ?)";

	# make $sql1 from a string into a real statement
	$stmt1 = mysqli_prepare($conn, $sql1);

	# bind input variables into the sql statement and execute statement
	try
	{
		mysqli_stmt_bind_param($stmt1, "ssss", $first_name, $last_name, $email, $password);
		mysqli_stmt_execute($stmt1);
		mysqli_stmt_close($stmt1);
	
		#echo "adding to user table successful";
	} 
	catch(mysqli_sql_exception $e) 
	{
		# if there is an issue, it would probably be a unique constraint violation. send error to url
		header("Location: signup.html?error=email_taken");
		exit();
	}

	# get the ID of the currently added user (i did this to ensure that id for user is same for parent)
	$id = mysqli_insert_id($conn);

	# add to parent table
	$sql2 = "INSERT INTO parent()
			VALUES (?)";

	$stmt2 = mysqli_prepare($conn, $sql2);

	try
	{
		mysqli_stmt_bind_param($stmt2, "i", $id);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_close($stmt2);
	
		#echo "adding to parent table successful";

		# save current info so user has access to it even after going to different pages
		$_SESSION["user_id"] = $id;
		$_SESSION["first_name"] = $first_name;

		# redirect back to the html file
		header("Location: signup.html?success=1");
	} 
	catch(mysqli_sql_exception $e) {
		echo "unable to add parent into table";
	}
?>