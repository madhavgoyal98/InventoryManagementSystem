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
		require_once("../../config/orders.php");
		require_once("../../config/input_cleaning.php");

		$database = new Database();
		$conn = $database->getConnection();

		$order = new Orders($conn);

		$order->readOne($id);
	?>

	<?php 
		// if the form was submitted
		if($_POST)
		{
			$order->vendor = sanitizeMySQL($conn, $_POST['vendor']);
			$order->start_date = sanitizeMySQL($conn, $_POST['sdate']);
			$order->end_date = sanitizeMySQL($conn, $_POST['edate']);
			
			for($i = 0; $i < count($order->fp); $i++)
			{
				$order->fp[$i][2] = $_POST[$order->fp[$i][3]];
			}
			
			// update the item
			if( $order->update($id) )
			{
				echo("<div class='alert alert-success alert-dismissable'>");
					echo("Item details updated.");
				echo("</div>");
				
				//get updated values
				$order->readOne($id);
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
					<td style="width: 30%;">Vendor</td>
					<td style="width: 40%;"><input type='text' name='vendor' class='form-control' value='<?php echo($order->vendor); ?>' maxlength="50" oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Start Date</td>
					<td><input type="date" name='sdate' class='form-control' value='<?php echo($order->start_date); ?>' required></td>
				</tr>
				
				<tr>
					<td style="width: 30%;">End Date</td>
					<td><input type="date" name='edate' class='form-control' value='<?php echo($order->end_date); ?>' required></td>
				</tr>
				
				<tr>
					<td style="width: 30%;">Qty. made</td>
					<td>
						<table class='table table-hover table-responsive' style="width: 100%;">
							<?php		
								for($i = 0; $i < count($order->fp); $i++)
								{
									$n = $order->fp[$i][3];
									$v = $order->fp[$i][2];
									
									echo("<tr>");
										echo("<td>". $order->fp[$i][0]. "</td>");
										echo("<td style='border-right: solid 2px;'>". "<input type='number' name='$n' value=$v min='0' class='form-control' maxlength='5' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
																				
										$i++;
									
										if($i < count($order->fp))
										{
											$n = "fp". $order->fp[$i][3];
											$v = $order->fp[$i][2];
											
											echo("<td>". $order->fp[$i][0]. "</td>");
											echo("<td>". "<input type='number' name='$n' value='$v' class='form-control' maxlength='5' min='0' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
										}
									echo("</tr>");
								}
							
								unset($result);
								unset($row);
							?>
						</table>
					</td>
				</tr>

				<tr>
					<td></td>
					<td>
						<button type="submit" class="btn btn-primary">Update</button>
					</td>
				</tr>

			</table>
		</form>
		
	</div>
	<?php

		echo("<div class='right-button-margin'>");
			echo("<a href='admin_orders.php' class='btn btn-default pull-right'>Back</a>");
		echo("</div>");

	?>

	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
	