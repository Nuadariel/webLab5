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
	// $dbhost='localhost: 3306';
	// $dbuser='root';
	// $dbpwd='';
	// $dbname='table';
	// $con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
	// if($con){
	// 	$id = mysqli_real_escape_string($con,trim($_SESSION['seeID']));
	// 	$sql = "SELECT * FROM users WHERE id='$id'";
	// 	$result = mysqli_query($con,$sql);
	// 	$row=mysqli_fetch_assoc($result);
	$id = mysqli_real_escape_string($db->get_connect(),trim($_SESSION['seeID']));
	$result = $db->do_sql("SELECT * FROM users WHERE id='$id'");
	$row=mysqli_fetch_assoc($result);
			echo '<center>
			<p>
				<img width="200" height="100" src="'.$row["img"].'">
			</p>
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
			</center>';
			$db->disconnect_DB();
	
?>
</body>
