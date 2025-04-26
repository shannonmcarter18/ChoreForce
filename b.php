<?php

	session_start();

	$db_host = "localhost";
	$db_user = "root";
	$db_pswd = "";
	$db_name = "choreforce";
	$conn = "";

	$conn = new mysqli($db_host, $db_user, $db_pswd, $db_name);

	$conn ->set_charset("utf8mb4");
	$username = $_GET['user'];
	$password = trim($_GET['pswd']);
	$email = trim($_GET['email']);

	$sql = "UPDATE user SET email = '$email' WHERE id = '$username' AND password = '$password'";

	$result = $conn -> query($sql);

	$conn -> close();
?>
