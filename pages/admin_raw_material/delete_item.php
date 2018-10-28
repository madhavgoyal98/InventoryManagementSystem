<?php
	
	if($_POST)
	{
		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/raw_material.php");
		
		$database = new Database();
		$conn = $database->getConnection();

		$rm = new RawMaterial($conn);
		
		
		// delete the item
		if($rm->delete($_POST['object_id']))
		{
			echo("Object was deleted.");
		}
		else
		{
			echo("Unable to delete object.");
		}
	}

?>