<?php
	if (isset($_POST['register'])) {
		
		$password=trim($_POST['password1']);
    	$repeatpassword=trim($_POST['password2']);
    		if ($_FILES && $_FILES['image']['error']== UPLOAD_ERR_OK){
        		$dbhost='localhost: 3306';
				$dbuser='root';
				$dbpwd='';
				$dbname='table';
				$con=mysqli_connect($dbhost,$dbuser,$dbpwd,$dbname);
				
				$sql="SELECT * FROM users";
				$datab=mysqli_query($con,$sql);
				$user_contains=FALSE;
				while($row=mysqli_fetch_assoc($datab)){
					if (!strcasecmp($_POST['login'], $row['login'])) {

						echo "Пользователь с таким логином уже существует";
    						$user_contains=TRUE;
    						mysqli_close($con);
					}
				}

				if(!$user_contains)
				if($con){
					$role;
					if (isset($_SESSION['role']) && !strcasecmp($_SESSION['role'], "admin")) {
						$role=$_POST['role'];
					}else $role="user";
					$sql = "INSERT INTO users (login, pwd, fname, lname, role) VALUES ('".$_POST['login']."','". $_POST['password1']."','". $_POST['fname']."','".$_POST['lname']."','".$role."')";
					if(mysqli_query($con,$sql)){
					//картинка
					$sql = "SELECT * FROM users WHERE login='".$_POST['login']."'";
					$row=mysqli_fetch_assoc(mysqli_query($con,$sql));
					$name_img = 'imgs/'.$row['id'].'.img';
        			move_uploaded_file($_FILES['image']['tmp_name'], "C:/xampp/htdocs/Proj2.1/".$name_img);
        			$sql="UPDATE users SET img='$name_img' WHERE login='".$_POST['login']."'";
        			mysqli_query($con,$sql);

					mysqli_close($con);
				}else{
					echo "Не удалось соединится с бд";			
    			}
    		}
    	}else{
    			echo "Пароли не совпадают";
    		}
	}
?>