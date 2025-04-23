<?php

	session_start();

	$db_host = "localhost";
	$db_user = "root";
	$db_pswd = "";
	$db_name = "choreforce";
	$conn = "";

	try{
		$conn = mysqli_connect($db_host, $db_user, $db_pswd, $db_name);
	}
	catch(mysqli_sql_exception $e)
	{
		echo "unable to connect<br>";
		exit();
	}

	$conn ->set_charset("utf8mb4");
	$username = $_POST["username"];
	$password = $_POST["password"];

	$sql1 = "SELECT password
			 FROM user 
			 WHERE id = ?";

	$stmt1 = mysqli_prepare($conn, $sql1);

	try
	{
		mysqli_stmt_bind_param($stmt1, "i", $username);
		mysqli_stmt_execute($stmt1);
	
		//echo "select successful<br>";
	} 
	catch(mysqli_sql_exception $e) 
	{
		echo "select failed<br>";
		exit();
	}

	// get the result of the select query
	$result = mysqli_stmt_get_result($stmt1);

	// close the sql statement (this used to be in the try catch but i moved it after getting result cuz i still needed the statement)
	mysqli_stmt_close($stmt1);
	// puts result in an array with attribute names as indices
	$r = mysqli_fetch_assoc($result);

	//echo $r["password"];

	if($r === null || trim($r["password"]) != $password) // if sql query has no results or no matching result
	{
		echo $password;
		echo $r["password"];
		echo $r["password"];
		
		header("Location: signin.html?error=invalid_login");
		exit();
	}
	else if(trim($r["password"]) == $password) // if results find same user and same password
	{
		//echo "match!<br>";

		// keeps user id saved for reference once page redirects to one of the portals (so you can get access to user's info in the portal pages)
		$_SESSION['user_id'] = $r['id'];

		// check whether user is a parent
		$sql2 = "SELECT * 
				 FROM parent
				 WHERE id = ?";

		$stmt2 = mysqli_prepare($conn, $sql2);

		try
		{
			mysqli_stmt_bind_param($stmt2, "i", $username);
			mysqli_stmt_execute($stmt2);
	
			echo "select successful<br>";
		} 
		catch(mysqli_sql_exception $e) 
		{
			echo "select failed<br>";
			exit();
		}

		$res2 = mysqli_stmt_get_result($stmt2);
		mysqli_stmt_close($stmt2);
		$r2 = mysqli_fetch_assoc($res2);
		
		if($r2 === null) // if id not found in parent table, then id belongs to a child
		{
			// i just realized i can redirect to a different html file from php instead of how i did it for signups
			header("Location: childportal.html");
			exit();
		}
		else
		{
			//echo "here it is ";
			//echo count($r2);
			header("Location: parentportal.html");
			exit();
		}

	}


?>