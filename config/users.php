<?php
	class Users
	{
		private $conn;
		private $user_id;
		private $password;
		private $role;
		
		public function __construct($connection, $user_id, $password)
		{
			$this->conn = $connection;
			$this->user_id = $user_id;
			$this->password = $password;
		}
		
		public function authenticate() //function to login the user
		{
			$query = "SELECT * FROM users WHERE uid = '$this->user_id';"; //retreiving row corresponding to username entered
			$result = $this->conn->query($query);
			
			if(!$result)
			{
				die($this->conn->connect_error);
			}
			else
			{
				$row = $result->fetch_array(MYSQLI_NUM);
				
				$result->free();
				
				if(password_verify($this->password, $row[1])) //verifying the encrypted password
				{
					$this->role = $row[3];
					
					session_start();
					$_SESSION['role'] = $this->role; //storing role in session variable
					
					return("1"); //if username and password match
				}
				else
				{
					return("0");
				}
			}
		}
	}
?>