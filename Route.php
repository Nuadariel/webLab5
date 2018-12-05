<?php  
	session_start();
	require_once('PHP/DB.php');
	class FuncController{
		private $DataBase;
		
		function __construct(){
			$this->DataBase=new DB();
		}
		public function search($search_login){
			$this->DataBase->connect_DB();
			$rows = array();
			$result = $this->DataBase->do_sql("SELECT * FROM users WHERE login LIKE '%".$search_login."%'");
			while ($row = mysqli_fetch_assoc($result)){
    			$rows[] = $row;
			}
			$this->DataBase->disconnect_DB();
			return $rows;
		}
		public function get_user_role(){
			if (isset($_SESSION['role'])) {
				return $_SESSION['role'];
			}else
				return "none";
		}
		public function sort(){
			$this->DataBase->connect_DB();
			$rows = array();
			$result = $this->DataBase->do_sql("SELECT * FROM users ORDER BY id DESC");
			while ($row = mysqli_fetch_assoc($result)){
    			$rows[] = $row;
			}
			$this->DataBase->disconnect_DB();
			return $rows;
		}
		public function getelementsdb(){
			$this->DataBase->connect_DB();
			$rows = array();
			$result = $this->DataBase->do_sql("SELECT * FROM users");
			while ($row = mysqli_fetch_assoc($result)){
    			$rows[] = $row;
			}
			$this->DataBase->disconnect_DB();
			return $rows;
		}
	}

	class UserController{
		private $DataBase;
		
		function __construct(){
			$this->DataBase=new DB();
		}
		public function log_in($login,$password){
			$this->DataBase->connect_DB();
    		$result = $this->DataBase->do_sql("SELECT login, id, role FROM users WHERE login='$login' AND pwd='$password'");
    		$row=mysqli_fetch_assoc($result);
    		if (!mysqli_num_rows($result)) {
    			$this->DataBase->disconnect_DB();
    			return 'Такого пользователя нет';
    		}else{
    			$_SESSION['id']=$row['id'];
    			$_SESSION['role']=$row['role'];
    			$this->DataBase->disconnect_DB();
    			return '';
    		}
		}
		function edit($edit_id, $login, $pwd, $fname, $lname, $role){
			$this->DataBase->connect_DB();

			$row=mysqli_fetch_assoc($this->DataBase->do_sql("SELECT * FROM users WHERE id='$edit_id'"));
			$name_img;
			if (!strcasecmp($role,''))
				$role=$row['role'];

			if (strlen($login)==0) {
				$login=$row['login'];
			}elseif (strcasecmp($login, $row['login']) && $this->check_isset_user($login)) {
				echo "Пользователь с таким логином уже существует";
				$login=$row['login'];
			}

			if (strlen($fname)==0) {
				$fname=$row['fname'];
			}
			if (strlen($lname)==0) {
				$lname=$row['lname'];
			}
			if (strlen($pwd)==0) {
				$pwd=$row['pwd'];
			}

			if ($_FILES && $_FILES['image']['error']== UPLOAD_ERR_OK){
	        		$name_img = 'imgs/'.$edit_id.'.jpeg';
	        		move_uploaded_file($_FILES['image']['tmp_name'], $name_img);
	        }else
	        	$name_img=$row['img'];

	        $this->DataBase->do_sql("UPDATE users SET login='$login', fname='$fname', lname='$lname', pwd='$pwd', img='$name_img',role='$role' WHERE id='$edit_id'");


			$this->DataBase->disconnect_DB();
		}

		function register($login, $pwd, $fname, $lname, $role){
			$this->DataBase->connect_DB();
			if ($_FILES && $_FILES['image']['error']== UPLOAD_ERR_OK){
				if ($this->check_isset_user($login))
					echo "Пользователь с таким логином уже существует";
				else{
					$this->DataBase->do_sql("INSERT INTO users (login, pwd, fname, lname, role) VALUES ('".$login."','". $pwd."','".$fname."','".$lname."','".$role."')");
					//картинка
					// $sql = "SELECT * FROM users WHERE login='".$login."'";
					$row=mysqli_fetch_assoc($this->DataBase->do_sql("SELECT * FROM users WHERE login='".$login."'"));
					$name_img = 'imgs/'.$row['id'].'.jpeg';
        			move_uploaded_file($_FILES['image']['tmp_name'], "C:/xampp/htdocs/Proj5/".$name_img);
        			// $sql="UPDATE users SET img='$name_img' WHERE login='".$login."'";
        			$this->DataBase->do_sql("UPDATE users SET img='$name_img' WHERE login='".$login."'");
				}
			}
			$this->DataBase->disconnect_DB();
		}
		function check_isset_user($login){
			$datab=$this->DataBase->do_sql("SELECT * FROM users");
			while($row=mysqli_fetch_assoc($datab))
				if (!strcasecmp($login, $row['login']))
					return TRUE;
			return FALSE;
		}

		public function see_id($see_id){
			$_SESSION['seeID']=$see_id;
		}
		public function edit_id($edit_id){
			$_SESSION['edit_id']=$edit_id;
		}
		public function delete($delete_id){
			$this->DataBase->connect_DB();
			$this->DataBase->do_sql("DELETE FROM users WHERE id = '".$delete_id."'");
			$this->DataBase->disconnect_DB();
		}

		public function exit(){
			$_SESSION=array();
		}
	}

if (($_POST['model']=='user')&&($_POST['action']=='register')) {
	$role;
	if (isset($_SESSION['role']) && !strcasecmp($_SESSION['role'], "admin")) {
		$role=$_POST['role'];
	}else $role="user";
	$password=trim($_POST['password1']);
    $repeatpassword=trim($_POST['password2']);
    if (strcasecmp($password, $repeatpassword)) {
    	echo "Пароли не совпадают";
    	return;
    }
	$uc=new UserController();
	$uc->register($_POST['login'],$password,$_POST['fname'],$_POST['lname'],$role);
}

if (($_POST['model']=='user')&&($_POST['action']=='exit')) {
	$uc=new UserController();
	$uc->exit();
}
if (($_POST['model']=='user')&&($_POST['action']=='log_in')) {
	$login = trim($_POST['login']);
    $password = trim($_POST['password']);
	$uc=new UserController();
	echo $uc->log_in($login,$password);
}
if (($_POST['model']=='user')&&($_POST['action']=='edit')) {
	//on fact
	$id;
	if (isset($_SESSION['edit_id'])) {
		$id =trim($_SESSION['edit_id']);
	}else
		$id =trim($_SESSION['id']);
	$role='';
	if (isset($_POST['role']) && (strlen($_POST['role'])!=0)) {
		$role=$_POST['role'];
	}
	$uc=new UserController();
	$uc->edit($id,$_POST['login'],$_POST['password'],$_POST['first_name'],$_POST['last_name'],$role);
}

if (($_POST['model']=='functional')&&($_POST['action']=='search')) {
	$fc=new FuncController();
	echo json_encode($fc->search($_POST['search']));
}

if (($_POST['model']=='functional')&&($_POST['action']=='get_user_role')) {
	$fc=new FuncController();
	echo $fc->get_user_role();
}

if (($_POST['model']=='functional')&&($_POST['action']=='sort')) {
	$fc=new FuncController();
	echo json_encode($fc->sort());
}
if (($_POST['model']=='functional')&&($_POST['action']=='getelementsdb')) {
	$fc=new FuncController();
	echo json_encode($fc->getelementsdb());
}
if (($_POST['model']=='user')&&($_POST['action']=='save_id_see')) {
	$uc=new UserController();
	$uc->see_id($_POST['see_profile']);
}
if (($_POST['model']=='user')&&($_POST['action']=='save_id_edit')) {
	$uc=new UserController();
	$uc->edit_id($_POST['edit']);
}
if (($_POST['model']=='user')&&($_POST['action']=='delete')) {
	$uc=new UserController();
	$uc->delete_id($_POST['delete']);
}
?>