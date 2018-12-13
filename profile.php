<?php
	session_start();
	require_once 'PHP/DB.php';
?>
<html>
<head>

	<title></title>
</head>
<body>
	<a href="index.php">На главную</a>
	<?php
	$db=new DB();
	$db->connect_DB();
	$id = mysqli_real_escape_string($db->get_connect(),trim($_SESSION['seeID']));
	$result = $db->do_sql("SELECT * FROM users WHERE id='$id'");
	$row=mysqli_fetch_assoc($result);
			echo '<left>
			<p>
			login
			<input type="text" name="login" value="'.$row["login"].'" readonly>
			</p>
			<p>
			first name
			<input type="text" name="first_name" value="'.$row["fname"].'" readonly>
			</p>
			<p>
			last name
			<input type="text" name="last_name" value="'.$row["lname"].'" readonly>
			</p>
			<p>
			role
			<input type="text" name="role" value="'.$row["role"].'" readonly>
			</p>
			</left';
			$db->disconnect_DB();
	
?>
</body>
