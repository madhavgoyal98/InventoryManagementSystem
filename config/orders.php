<?php
	require_once("../../config/interface_items.php");

	class Orders implements Items
	{
		private $conn;
		
		public $order_id;
		public $start_date;
		public $end_date;
		public $vendor;
		public $fp = array(); //(array('id', 'qty req'))  or (array('name', 'qty req', 'qty made')) 
		
		public function __construct($connection)
		{
			$this->conn = $connection;
		}
		
		public function readAll($from_record_num, $records_per_page)
		{
			$query = "SELECT
						*
					FROM
						orders
					ORDER BY
						vendor ASC
					LIMIT
						{$from_record_num}, {$records_per_page}";

			$result = $this->conn->query( $query );

			return $result;
		}
		
		// used for paging products
		public function countAll()
		{
			$query = "SELECT COUNT(*) FROM orders";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);

			return $row[0];
		}
		
		public function readOne($id)
		{
			$this->fp = array();
			
			$query = "SELECT
						vendor, start_date, end_date
					FROM
						orders
					WHERE
						order_id = '$id'
					LIMIT
						0,1";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);
			
			$this->vendor = $row[0];
			$this->start_date = $row[1];
			$this->end_date = $row[2];
			
			
			//getting data from finished_order table				
			//getting the list of finished products
			$query = "SELECT finished_product.name, finished_order.quantity_req, finished_order.quantity_made, finished_order.fp_id FROM finished_product, finished_order WHERE finished_product.fp_id = finished_order.fp_id && finished_order.order_id = '$id'; ";
			
			$result = $this->conn->query($query);

			while($row = $result->fetch_array(MYSQLI_NUM))
			{
				$this->fp[] = array($row[0], $row[1], $row[2], $row[3]);
			}
		}
		
		public function update($id)
		{			
			try
			{
				$this->conn->query("BEGIN");
				
				//updating the quantity
				$query = "UPDATE
						orders
					SET
						vendor = '$this->vendor',
						start_date = '$this->start_date',
						end_date = '$this->end_date'
					WHERE
						order_id = '$id'";
				
				$result = $this->conn->query($query);
				
				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				
				for($i = 0; $i < count($this->fp); $i++)
				{
					$fp_id = $this->fp[$i][3];
					
					//getting the old quantity
					$query = "SELECT quantity_made FROM finished_order WHERE order_id = '$id' && fp_id = '$fp_id'; ";
					$result = $this->conn->query($query);

					if(!$result)
					{
						$result->free();
						throw new Exception($this->conn->error);
					}
					
					$row = $result->fetch_array(MYSQLI_NUM);
					$old_quantity = $row[0];


					//updating quantity of finished product
					$x = ($this->fp[$i][2] - $old_quantity) * $this->fp[$i][1];

					$query = "UPDATE finished_product SET quantity = quantity - {$x} WHERE fp_id = '$fp_id'; ";
					$result = $this->conn->query($query);

					if(!$result)
					{
						$result->free();
						throw new Exception($this->conn->error);
					}
					
					
					//updating quantity of finished product in finished_order table
					$query = "UPDATE finished_order SET quantity_made = {$this->fp[$i][2]} WHERE order_id = '$id' && fp_id = '$fp_id'; ";
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

			$query = "DELETE FROM orders WHERE order_id = '$id'";

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
							orders(vendor, start_date, end_date)
							VALUES(
							'$this->vendor', 
							'$this->start_date', 
							'$this->end_date');";

				$result = $this->conn->query($query);


				if(!$result)
				{
					$result->free();
        			throw new Exception($this->conn->error);
				}
				
				
				$inserted_id = $this->conn->insert_id;
				
				//insertion for intermediate used
				foreach($this->fp as $i)
				{
					if($i[1] != 0)
					{
						$query = "INSERT INTO finished_order VALUES('$inserted_id', '$i[0]', '0', '$i[1]')";
					
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