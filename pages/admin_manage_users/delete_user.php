<?php
	
	if($_POST)
	{
		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/users.php");
		
		$database = new Database();
		$conn = $database->getConnection();

		$user = new Users($conn, "", "");
		
		
		// delete the user
		if($user->delete($_POST['object_id']))
		{
			echo("Object was deleted.");
		}
		else
		{
			echo("Unable to delete object.");
		}
	}

?>