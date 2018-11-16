<?php
	class Intermediate
	{
		private $conn;
		
		public $im_id;
		public $rm_used = array();   //(id=>quantity)
		public $im_used = array();   //(id=>quantity)
		public $name;
		public $quantity;
		public $measuring_unit;
		
		public function __construct($connection)
		{
			$this->conn = $connection;
		}
		
		public function readAll($from_record_num, $records_per_page)
		{
			$query = "SELECT
						*
					FROM
						intermediate_items
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
			$query = "SELECT COUNT(*) FROM intermediate_items";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);

			return $row[0];
		}
		
		public function readOne($id)
		{
			$query = "SELECT
						name, quantity, measuring_unit
					FROM
						intermediate_items
					WHERE
						rm_id = '$id'
					LIMIT
						0,1";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);
			
			return($row);
		}
		
		public function update($id)
		{			
			$query = "UPDATE
						intermediate_items
					SET
						name = '$this->name',
						quantity = '$this->quantity',
						measuring_unit = '$this->measuring_unit'
					WHERE
						im_id = '$id'";

			$result = $this->conn->query($query);

			if($result)
			{
				return true;
			}

			return false;
		}
		
		// delete the item
		public function delete($id)
		{

			$query = "DELETE FROM intermediate_items WHERE im_id = '$id'";

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
				
		// create item
		public function create()
		{			
			try
			{
				$this->conn->query("BEGIN");
				
				
				$query = "INSERT INTO
							intermediate_items(name, quantity, measuring_unit)
							VALUES(
							'$this->name', 
							'$this->quantity', 
							'$this->measuring_unit');";

				$result = $this->conn->query($query);


				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				
				$inserted_id = $this->conn->insert_id;
				
				//insertion for raw material used
				foreach($this->rm_used as $id=>$quan)
				{
					$query = "INSERT INTO raw_intermediate VALUES('$id', DEFAULT, '$inserted_id', '$quan', DEFAULT);";
					
					$result = $this->conn->query($query);


					if(!$result)
					{
						echo($this->conn->error);
						throw new Exception($this->conn->error);
					}
				}
				
				//insertion for intermediate used
				foreach($this->im_used as $id=>$quan)
				{
					$query = "INSERT INTO raw_intermediate VALUES(DEFAULT, '$id', '$inserted_id', DEFAULT, '$quan')";
					
					$result = $this->conn->query($query);


					if(!$result)
					{
						echo($this->conn->error);
						throw new Exception($this->conn->error);
					}
				}
				
				$this->conn->query("COMMIT");
				
				return true;
			}
			catch(Exception $e)
			{
				$this->conn->query("ROLLBACK");
				
				return false;
			}
		}
	}
?>