<?php
	
	if($_POST)
	{
		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/orders.php");
		
		$database = new Database();
		$conn = $database->getConnection();

		$order = new Orders($conn);
		
		
		// delete the item
		if($order->delete($_POST['object_id']))
		{
			echo("Object was deleted.");
		}
		else
		{
			echo("Unable to delete object.");
		}
	}

?>