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
	catch(mysqli_sql_exception $e){
		echo "unable to connect";
	}

	

    
?>