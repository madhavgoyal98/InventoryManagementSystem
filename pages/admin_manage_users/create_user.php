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

		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/users.php");
		require_once("../../config/input_cleaning.php");

		$database = new Database();
		$conn = $database->getConnection();

		$user = new Users($conn, "", "");

	?>
	
	<?php
	
		// if the form was submitted
		if($_POST)
		{
			$name = sanitizeMySQL($conn, $_POST['name']);
			$username = sanitizeMySQL($conn, $_POST['username']);
			$password =sanitizeMySQL($conn, $_POST['password']);
			$role = sanitizeMySQL($conn, $_POST['role']);
				
			// create the user
			if($user->create($name, $username, $password, $role))
			{
				echo("<div class='alert alert-success'>User was created.</div>");
			}
			else
			{
				echo("<div class='alert alert-danger'>Unable to create user.</div>");
			}
		}
	?>
	
	<div class="table-responsive" style="width:40%;">
		
		<form action="create_user.php" method="post">
			<table class='table table-hover table-responsive table-bordered' style="width: 100%">

				<tr>
					<td style="width: 30%;">Name</td>
					<td style="width: 40%;"><input type='text' name='name' class='form-control' maxlength="40" required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Username</td>
					<td><input type='text' name='username' class='form-control' maxlength="30" required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Password</td>
					<td><input type='password' name='password' class='form-control' maxlength="30" required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Role</td>
					<td>
						<input type="radio" name="role" value="admin"> Admin<br>
						<input type="radio" name="role" value="worker" checked> Worker
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<button type="submit" class="btn btn-primary">Create</button>
					</td>
				</tr>

			</table>
		</form>
		
	</div>
	
	<div class='right-button-margin'>
		<a href='admin_manage_users.php' class='btn btn-default pull-right'>Back</a>
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
