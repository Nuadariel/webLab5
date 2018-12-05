<?php 
	session_start();
	if (isset($_POST['search'])) {
		$dbhost='localhost: 3306';
		$dbuser='root';
		$dbpwd='';
		$dbname='table';
		$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
		if($con){
			$rows = array();
			$result = mysqli_query($con,"SELECT * FROM users WHERE login LIKE '%".$_POST['search']."%'");
			while ($row = mysqli_fetch_assoc($result)){
    			$rows[] = $row;
			}
    		mysqli_close($con);
		}
		echo json_encode($rows);
	}

	if (isset($_POST['sort'])) {
		$dbhost='localhost: 3306';
		$dbuser='root';
		$dbpwd='';
		$dbname='table';
		$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
		if($con){
			$rows = array();
			$result = mysqli_query($con,"SELECT * FROM users ORDER BY id DESC");
			while ($row = mysqli_fetch_assoc($result)){
    			$rows[] = $row;
			}
    		mysqli_close($con);
		}
		echo json_encode($rows);
	}

	if (isset($_POST['getelementsdb'])) {
		$dbhost='localhost: 3306';
		$dbuser='root';
		$dbpwd='';
		$dbname='table';
		$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
		if($con){
			$rows = array();
			$result = mysqli_query($con,"SELECT * FROM users");
			while ($row = mysqli_fetch_assoc($result)){
    			$rows[] = $row;
			}
    		mysqli_close($con);
		}
		echo json_encode($rows);
	}

	if (isset($_POST['get_user_role'])) {
		if (isset($_SESSION['role'])) {
			echo $_SESSION['role'];
		}else
		echo "none";
	}

	if (isset($_POST['delete'])) {
		$dbhost='localhost: 3306';
		$dbuser='root';
		$dbpwd='';
		$dbname='table';
		$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
    	if($con){
			$sql="DELETE FROM users WHERE id = '".$_POST['delete']."'";
			mysqli_query($con,$sql);
			mysqli_close($con);
    	}
    	unlink('imgs/'.$_POST['delete'].'.img');
	}
	if (isset($_POST['edit'])) {
		$_SESSION['edit_id']=$_POST['edit'];
	}
	if (isset($_POST['see_profile'])) {
		$_SESSION['seeID']=$_POST['see_profile'];
	}
	if (isset($_POST['exit'])){
		$_SESSION=array();
	}
	
	if (isset($_POST['log_in'])) {
		$password=trim($_POST['password']);
		$dbhost='localhost: 3306';
		$dbuser='root';
		$dbpwd='';
		$dbname='table';
		$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
    	if($con){
    		$login = mysqli_real_escape_string($con,trim($_POST['login']));
    		$password = mysqli_real_escape_string($con,trim($_POST['password']));
    		$sql = "SELECT login, id, role FROM users WHERE login='$login' AND pwd='$password'";
    		$result = mysqli_query($con,$sql);
    		$row=mysqli_fetch_assoc($result);
    		if (!mysqli_num_rows($result)) {
    			echo 'Такого пользователя нет';
    		}else{
    			$_SESSION['id']=$row['id'];
    			$_SESSION['role']=$row['role'];
    			echo $row['role'];
    		}
		}
    	mysqli_close($con);
      	}
?>