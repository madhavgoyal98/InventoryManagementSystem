<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Item</title>
	<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
</head>
	
<body style="background-color:transparent; margin-top: 8%; margin-left: 5%">
	
	<?php

		// get ID of the item to be edited
		$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');

		//importing class files
		require_once("../../config/database.php");
		require_once("../../config/intermediate.php");
		require_once("../../config/input_cleaning.php");

		$database = new Database();
		$conn = $database->getConnection();

		$im = new Intermediate($conn);

		$im_details = $im->readOne($id);
	?>

	<?php 
		// if the form was submitted
		if($_POST)
		{
			$im->name = sanitizeMySQL($conn, $_POST['name']);
			$im->quantity = sanitizeMySQL($conn, $_POST['quantity']);
			$im->measuring_unit = sanitizeMySQL($conn, $_POST['measuring']);
			
			// update the item
			if( $im->update($id) )
			{
				echo("<div class='alert alert-success alert-dismissable'>");
					echo("Item details updated.");
				echo("</div>");
				
				//get updated values
				$im_details = $im->readOne($id);
			}

			// if unable to update the product, tell the user
			else
			{
				echo("<div class='alert alert-danger alert-dismissable'>");
					echo("Unable to update item details.");
				echo("</div>");
			}
		}
	?>
	
	<div class="table-responsive" style="width:40%;">
		
		<form action="<?php echo('update_item.php?id='.$id.''); ?>" method="post">
			<table class='table table-hover table-responsive table-bordered' style="width: 100%">

				<tr>
					<td style="width: 30%">Name</td>
					<td><input type='text' name='name' value='<?php echo($rm_details[0]); ?>' class='form-control' readonly></td>
				</tr>

				<tr>
					<td style="width: 30%">Quantity</td>
					<td><input type='number' name='quantity' value='<?php echo($rm_details[1]); ?>' class='form-control' maxlength="11" oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' required></td>
				</tr>
				
				<tr>
					<td style="width: 30%">Measuring Unit</td>
					<td><input type='text' name='measuring' value='<?php echo($rm_details[2]); ?>' class='form-control' readonly></td>
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
			echo("<a href='worker_intermediate_items.php' class='btn btn-default pull-right'>Back</a>");
		echo("</div>");

	?>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
	