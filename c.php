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

	$sql = "SELECT email FROM user WHERE id = ? AND password = ?";
	if($stmt = $conn -> prepare($sql))
	{
		$stmt -> bind_param("is", $username, $password);
		$stmt -> execute();

		$stmt -> bind_result($email);
		while($stmt -> fetch())
		{
			printf("%s", $email);
		}
	}

	$conn -> close();
?>
