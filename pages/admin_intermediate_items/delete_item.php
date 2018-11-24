<?php
	
	if($_POST)
	{
		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/intermediate.php");
		
		$database = new Database();
		$conn = $database->getConnection();

		$im = new Intermediate($conn);
		
		
		// delete the item
		if($im->delete($_POST['object_id']))
		{
			echo("Object was deleted.");
		}
		else
		{
			echo("Unable to delete object.");
		}
	}

?>