<?php

	session_start();

	$db_host = "localhost";
	$db_user = "root";
	$db_pswd = "";
	$db_name = "choreforce";
	$conn = "";

	$conn = new mysqli($db_host, $db_user, $db_pswd, $db_name);

	$conn ->set_charset("utf8mb4");
	$username = trim($_GET['user']);
	$password = trim($_GET['pswd']);

	$sql = "SELECT email FROM user WHERE id = '$username' AND password = '$password'";

	$result = $conn -> query($sql);

	if($result)
	{
		while($row = $result -> fetch_assoc())
		{
			printf("Email: %s\n",
			$row["email"]);
		}
		$result->free();
	}

	$conn -> close();
?>
