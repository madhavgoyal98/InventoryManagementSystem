<?php
	class RawMaterial
	{
		private $conn;
		
		public $rm_id;
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
						raw_material
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
			$query = "SELECT COUNT(*) FROM raw_material";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);

			return $row[0];
		}
		
		public function readOne($id)
		{
			$query = "SELECT
						name, quantity, measuring_unit
					FROM
						raw_material
					WHERE
						rm_id = '$id'
					LIMIT
						0,1";

			$result = $this->conn->query( $query );
			$row = $result->fetch_array(MYSQLI_NUM);
			
			$this->name = $row[0];
			$this->quantity = $row[1];
			$this->measuring_unit = $row[2];
		}
		
		public function update($id)
		{			
			$query = "UPDATE
						raw_material
					SET
						name = '$this->name',
						quantity = '$this->quantity',
						measuring_unit = '$this->measuring_unit'
					WHERE
						rm_id = '$id'";

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

			$query = "DELETE FROM raw_material WHERE rm_id = '$id'";

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
			$query = "INSERT INTO
						raw_material(name, quantity, measuring_unit)
					VALUES(
						'$this->name', '$this->quantity', '$this->measuring_unit')";

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