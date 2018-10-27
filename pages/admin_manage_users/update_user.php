<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
	
<body style="background-color:transparent; margin-top: 8%; margin-left: 5%">
	
	<?php

		// get ID of the user to be edited
		$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/users.php");
		require_once("../../config/input_cleaning.php");

		$database = new Database();
		$conn = $database->getConnection();

		$user = new Users($conn, "", "");

		$user_details = $user->readOne($id);
	?>

	<?php 
		// if the form was submitted
		if($_POST)
		{
			// update the product
			if( $user->update($id, sanitizeMySQL($conn, $_POST['name']), sanitizeMySQL($conn, $_POST['password']), $_POST['role']) )
			{
				echo("<div class='alert alert-success alert-dismissable'>");
					echo("User details updated.");
				echo("</div>");
			}

			// if unable to update the product, tell the user
			else
			{
				echo("<div class='alert alert-danger alert-dismissable'>");
					echo("Unable to update user details.");
				echo("</div>");
			}
		}
	?>
	
	<div class="table-responsive" style="width:40%;">
		
		<form action="<?php echo('update_user.php?id='.$id.''); ?>" method="post">
			<table class='table table-hover table-responsive table-bordered' style="width: 100%">

				<tr>
					<td style="width: 30%">Name</td>
					<td><input type='text' name='name' value='<?php echo($user_details[0]) ?>' class='form-control' maxlength="40"></td>
				</tr>

				<tr>
					<td style="width: 30%">Password</td>
					<td><input type='password' name='password' value='' class='form-control' required/></td>
				</tr>

				<tr>
					<td style="width: 30%">Role</td>
					<td>
						<input type="radio" name="role" value="admin" <?php if($user_details[1] == "admin"){ echo("checked"); }?> /> Admin<br>
						<input type="radio" name="role" value="worker" <?php if($user_details[1] == "worker"){ echo("checked"); }?> /> Worker
					</td>
				</tr>

				<tr>
					<td></td>
					<td style="width: 70%">
						<button type="submit" class="btn btn-primary">Update</button>
					</td>
				</tr>

			</table>
		</form>
		
	</div>
	<?php

		echo("<div class='right-button-margin'>");
			echo("<a href='admin_manage_users.php' class='btn btn-default pull-right'>Back</a>");
		echo("</div>");

	?>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
	