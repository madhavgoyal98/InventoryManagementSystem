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
					$arr = array("1", $this->role);
					
					return($arr); //if username and password match
				}
				else
				{
					return(array("0"));
				}
			}
		}
		
		public function readAll($from_record_num, $records_per_page)
		{
			$query = "SELECT
						uid, name, role
					FROM
						users
					ORDER BY
						name ASC
					LIMIT
						{$from_record_num}, {$records_per_page}";

			$result = $this->conn->query( $query );

			return $result;
		}
		
		// used for paging products
		public function countAll()
		{
			$query = "SELECT COUNT(*) FROM users";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);

			return $row[0];
		}
		
		public function readOne($id)
		{
			$query = "SELECT
						name, role
					FROM
						users
					WHERE
						uid = '$id'
					LIMIT
						0,1";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);
			
			return($row);
		}
		
		public function update($id, $name, $password, $role)
		{
			$p = password_hash($password, PASSWORD_DEFAULT);
			
			$query = "UPDATE
						users
					SET
						name = '$name',
						password = '$p',
						role = '$role'
					WHERE
						uid = '$id'";

			$result = $this->conn->query($query);

			if($result)
			{
				return true;
			}

			return false;
		}
		
		// delete the user
		public function delete($id)
		{

			$query = "DELETE FROM users WHERE uid = '$id'";

			$result = $this->conn->query($query);

			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
				
		// create user
		public function create($name, $username, $password, $role)
		{
			$p = password_hash($password, PASSWORD_DEFAULT);
			
			$query = "INSERT INTO
						users
					VALUES(
						'$username', '$p', '$name', '$role')";

			$result = $this->conn->query($query);


			if($result)
			{
				return true;
			}
			else
			{
				return false;
			}

		}
	}
?>