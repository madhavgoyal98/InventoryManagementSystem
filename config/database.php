<?php
	class Database
	{
		private $host = "localhost";
		private $db_name = "inventory";
		private $username = "root";
		private $password = "";
		public $conn;
		
		public function getConnection()
		{
			$this->conn = null;
			
			$this->conn = new MySQLi($this->host, $this->username, $this->password, $this->db_name);
			
			if($this->conn->connect_error)
			{
				die($this->conn->connect_error);
			}
			
			return($this->conn);
		}
		
		public function closeConnection()
		{
			$this->conn->close();
		}
	}
?>