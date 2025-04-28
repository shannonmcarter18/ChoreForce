<?php
    session_start();

    //establishing db connection
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
    $chore_id = $_POST["choreid"] ?? '';
    $chore_attribute = $_POST["chore_attribute"] ?? '';
	$chore_edit = $_POST["choreedit"] ?? '';

	#input validation
	if (!$chore_id || !$chore_attribute || !$chore_edit) {
		echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
		exit();
	}

	#update either the chore descriptiuon or the reward amount
	if($chore_id && $chore_attribute === 'chore_desc') {
		$sql = "UPDATE chore SET DESCRIPTION = ? WHERE CHORE_ID = ?";
		# make $sql1 from a string into a real statement
		$stmt1 = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt1, "si", $chore_edit, $chore_id);
		if(mysqli_stmt_execute($stmt1)){
			echo "<script>alert('Chore description changed.'); window.history.back();</script>";
		} else {
			echo "<script>alert('Error with processing reward amount change.'); window.history.back();</script>";
		}
		mysqli_stmt_close($stmt1);
	} elseif($chore_id && $chore_attribute === 'chore_reward') {
		$sql = "UPDATE chore SET REWARD_AMNT = ? WHERE CHORE_ID = ?";
		# make $sql1 from a string into a real statement to be executed
		$stmt2 = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt2, "si", $chore_edit, $chore_id);
		if(mysqli_stmt_execute($stmt2)){
			echo "<script>alert('Reward amount changed.'); window.history.back();</script>";
		} else {
			echo "<script>alert('Error with processing reward amount change.'); window.history.back();</script>";
		}
		mysqli_stmt_close($stmt2);
	} else {
		echo "<script>alert('Error: Chore edit not confirmed'); window.history.back();</script>";
	}
	
    $conn->close();
?>