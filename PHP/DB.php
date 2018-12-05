<?php
class DB{
		private $dbhost;
		private $dbuser;
		private $dbpwd;
		private $dbname;
		private $connect;
		function __construct(){
			$this->dbhost='localhost: 3306';
			$this->dbuser='root';
			$this->dbpwd='';
			$this->dbname='table';
			$this->connect/*=mysqli_connect($this->dbhost,$this->dbuser,$this->dbpwd,$this->dbname)*/;
		}
		function do_sql($sql){
			return mysqli_query($this->connect,$sql);
		}
		function connect_DB(){
			$this->connect=mysqli_connect($this->dbhost,$this->dbuser,$this->dbpwd,$this->dbname);
		}
		function disconnect_DB(){
			mysqli_close($this->connect);
			$con=NULL;
		}
		public function get_connect(){
			return $this->connect;
		}
	}
	?>