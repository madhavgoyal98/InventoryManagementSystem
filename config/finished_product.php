<?php
	class FinishedProduct
	{
		private $conn;
		
		public $fp_id;
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
						finished_product
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
			$query = "SELECT COUNT(*) FROM finished_product";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);

			return $row[0];
		}
		
		public function readOne($id)
		{
			$query = "SELECT
						name, quantity, measuring_unit
					FROM
						finished_product
					WHERE
						fp_id = '$id'
					LIMIT
						0,1";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);
			
			$this->name = $row[0];
			$this->quantity = $row[1];
			$this->measuring_unit = $row[2];
			
			
			//getting data from raw_intermediate table				
			//getting the list of intermediate items used
			$query = "SELECT intermediate_items.name, intermediate_finished.quantity_used FROM intermediate_items, intermediate_finished WHERE intermediate_items.im_id = intermediate_finished.im_id && intermediate_finished.fp_id = '$id'; ";
			
			$result = $this->conn->query($query);

			while($row = $result->fetch_array(MYSQLI_NUM))
			{
				$this->im_used[$row[0]] = $row[1];
			}
		}
		
		public function update($id)
		{			
			$this->im_used = array();
			
			try
			{
				$this->conn->query("BEGIN");
				
				//getting value of old quantity of intermediate item
				$query = "SELECT quantity from finished_product WHERE fp_id='$id';";
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
						finished_product
					SET
						name = '$this->name',
						quantity = '$this->quantity',
						measuring_unit = '$this->measuring_unit'
					WHERE
						fp_id = '$id'";
				
				$result = $this->conn->query($query);
				
				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				
				//getting the list of intermediate items used
				$query = "SELECT im_id, quantity_used FROM intermediate_finished WHERE fp_id = '$id'; ";
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

			$query = "DELETE FROM finished_product WHERE fp_id = '$id'";

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
							finished_product(name, quantity, measuring_unit)
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
				
				//insertion for intermediate used
				foreach($this->im_used as $id=>$quan)
				{
					if($quan != 0)
					{
						$query = "INSERT INTO intermediate_finished VALUES('$id', '$inserted_id', '$quan')";
					
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