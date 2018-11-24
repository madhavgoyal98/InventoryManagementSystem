<?php
	
	if($_POST)
	{
		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/finished_product.php");
		
		$database = new Database();
		$conn = $database->getConnection();

		$fp = new FinishedProduct($conn);
		
		
		// delete the item
		if($fp->delete($_POST['object_id']))
		{
			echo("Object was deleted.");
		}
		else
		{
			echo("Unable to delete object.");
		}
	}

?>