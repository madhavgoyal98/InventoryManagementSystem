<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Item</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
	
<body style="background-color:transparent; margin-top: 8%; margin-left: 5%">

	<?php

		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/raw_material.php");
		require_once("../../config/input_cleaning.php");

		$database = new Database();
		$conn = $database->getConnection();

		$rm = new RawMaterial($conn);

	?>
	
	<?php
	
		// if the form was submitted
		if($_POST)
		{
			$rm->name = sanitizeMySQL($conn, $_POST['name']);
			$rm->quantity = sanitizeMySQL($conn, $_POST['quantity']);
			$rm->measuring_unit = sanitizeMySQL($conn, $_POST['measuring']);
				
			// create the item
			if($rm->create())
			{
				echo("<div class='alert alert-success'>Item was created.</div>");
			}
			else
			{
				echo("<div class='alert alert-danger'>Unable to create item.</div>");
			}
		}
	?>
	
	<div class="table-responsive" style="width:40%;">
		
		<form action="create_item.php" method="post">
			<table class='table table-hover table-responsive table-bordered' style="width: 100%">

				<tr>
					<td style="width: 30%;">Name</td>
					<td style="width: 40%;"><input type='text' name='name' class='form-control' maxlength="100" required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Quantity</td>
					<td><input type='number' name='quantity' class='form-control' maxlength="11"></td>
				</tr>

				<tr>
					<td style="width: 30%;">Measuring Unit</td>
					<td><input type='text' name='measuring' class='form-control' maxlength="20" required></td>
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
		<a href="admin_raw_material.php" class='btn btn-default pull-right'>Back</a>
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
