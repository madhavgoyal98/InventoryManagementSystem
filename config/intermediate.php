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
						im_id = '$id'
					LIMIT
						0,1";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);
			
			$this->name = $row[0];
			$this->quantity = $row[1];
			$this->measuring_unit = $row[2];
			
			
			//getting data from raw_intermediate table
			//getting the list of raw materials used
				$query = "SELECT raw_material.name, raw_intermediate.rm_quantity_used FROM raw_material, raw_intermediate WHERE raw_material.rm_id = raw_intermediate.rm_id && raw_intermediate.im_id = '$id' && raw_intermediate.rm_id != 0; ";
				$result = $this->conn->query($query);
				
				while($row = $result->fetch_array(MYSQLI_NUM))
				{
					$this->rm_used[$row[0]] = $row[1];
				}
				
				
				//getting the list of intermediate items used
				$query = "SELECT intermediate_items.name, raw_intermediate.im_quantity_used FROM intermediate_items, raw_intermediate WHERE intermediate_items.im_id = raw_intermediate.im_im_id && raw_intermediate.im_id = '$id' && raw_intermediate.im_im_id != 0; ";
				$result = $this->conn->query($query);
				
				while($row = $result->fetch_array(MYSQLI_NUM))
				{
					$this->im_used[$row[0]] = $row[1];
				}
		}
		
		public function update($id)
		{			
			try
			{
				$this->conn->query("BEGIN");
				
				//getting value of old quantity of intermediate item
				$query = "SELECT quantity from intermediate_items WHERE im_id='$id';";
				$result = $this->conn->query($query);	
				
				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				$row = $result->fetch_array(MYSQLI_NUM);
				$old_quantity = $row[0];
				
				
				//updating the quantity
				$query = "UPDATE
						intermediate_items
					SET
						name = '$this->name',
						quantity = '$this->quantity',
						measuring_unit = '$this->measuring_unit'
					WHERE
						im_id = '$id'";
				
				$result = $this->conn->query($query);
				
				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				
				//getting the list of raw materials used
				$query = "SELECT rm_id, rm_quantity_used FROM raw_intermediate WHERE im_id = '$id'; ";
				$result = $this->conn->query($query);
				
				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				while($row = $result->fetch_array(MYSQLI_NUM))
				{
					$this->rm_used[$row[0]] = $row[1];
				}
				
				
				//getting the list of intermediate items used
				$query = "SELECT im_im_id, im_quantity_used FROM raw_intermediate WHERE im_id = '$id'; ";
				$result = $this->conn->query($query);
				
				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				while($row = $result->fetch_array(MYSQLI_NUM))
				{
					$this->im_used[$row[0]] = $row[1];
				}
				
				
				//updating quantity of raw material
				foreach($this->rm_used as $i=>$q)
				{
					$x = ($this->quantity - $old_quantity) * $q;
					
					$query = "UPDATE raw_material SET quantity = quantity - {$x} WHERE rm_id = '$i'; ";
					$result = $this->conn->query($query);
				
					if(!$result)
					{
						$result->free();
						throw new Exception($this->conn->error);
					}
				}
				
				
				//updating quantity of intermediate items
				foreach($this->im_used as $i=>$q)
				{
					$x = ($this->quantity - $old_quantity) * $q;
					
					$query = "UPDATE intermediate_items SET quantity = quantity - {$x} WHERE im_id = '$i'; ";
					$result = $this->conn->query($query);
				
					if(!$result)
					{
						$result->free();
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
					if($quan != 0)
					{
						$query = "INSERT INTO raw_intermediate VALUES('$id', DEFAULT, '$inserted_id', '$quan', DEFAULT);";
					
						$result = $this->conn->query($query);


						if(!$result)
						{
							echo($this->conn->error);
							throw new Exception($this->conn->error);
						}	
					}
				}
				
				//insertion for intermediate used
				foreach($this->im_used as $id=>$quan)
				{
					if($quan != 0)
					{
						$query = "INSERT INTO raw_intermediate VALUES(DEFAULT, '$id', '$inserted_id', DEFAULT, '$quan')";
					
						$result = $this->conn->query($query);


						if(!$result)
						{
							echo($this->conn->error);
							throw new Exception($this->conn->error);
						}	
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