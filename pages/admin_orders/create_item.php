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
		require_once("../../config/orders.php");
		require_once("../../config/input_cleaning.php");
		require_once("../../config/finished_product.php");

		$database = new Database();
		$conn = $database->getConnection();

		$order = new Orders($conn);
		$fp = new FinishedProduct($conn);

	?>
	
	<?php
	
		// if the form was submitted
		if($_POST)
		{
			$order->vendor = sanitizeMySQL($conn, $_POST['vendor']);
			$order->start_date = sanitizeMySQL($conn, $_POST['sdate']);
			$order->end_date = sanitizeMySQL($conn, $_POST['edate']);
			
			
			//getting quantities of finished product required
			$result = $fp->readAll(0, $fp->countAll());
			
			for($i = 0; $i < $result->num_rows; $i++)
			{
				$row = $result->fetch_array(MYSQLI_NUM);
				
				$order->fp[] = array($row[0], $_POST['fp_'. $row[0]]);
			}
			
			unset($result);
			unset($row);
			
			
			// create the item
			if($order->create())
			{
				echo("<div class='alert alert-success'>Item was created.</div>");
			}
			else
			{
				echo("<div class='alert alert-danger'>Unable to create item.</div>");
			}
		}
	?>
	
	<div class="table-responsive" style="width:80%;">
		
		<form action="create_item.php" method="post">
			<table class='table table-hover table-responsive table-bordered' style="width: 100%">

				<tr>
					<td style="width: 30%;">Vendor</td>
					<td style="width: 40%;"><input type='text' name='vendor' class='form-control' maxlength="50" oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Start Date</td>
					<td><input type="date" name='sdate' class='form-control' required></td>
				</tr>
				
				<tr>
					<td style="width: 30%;">End Date</td>
					<td><input type="date" name='edate' class='form-control' required></td>
				</tr>
				
				<tr>
					<td style="width: 30%;">Product</td>
					<td>
						<table class='table table-hover table-responsive' style="width: 100%;">
							<?php
								$result = $fp->readAll(0, $fp->countAll());
							
								for($i = 0; $i < $result->num_rows; $i++)
								{
									$row = $result->fetch_array(MYSQLI_NUM);
									
									echo("<tr>");
										echo("<td>". $row[1]. "</td>");
										echo("<td style='border-right: solid 2px;'>". "<input type='number' name='fp_$row[0]' value='0' class='form-control' maxlength='5' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
																				
										$i++;
									
										if($i < $result->num_rows)
										{
											$row = $result->fetch_array(MYSQLI_NUM);
											
											echo("<td>". $row[1]. "</td>");
											echo("<td>". "<input type='number' name='fp_$row[0]' value='0' class='form-control' maxlength='5' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
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
						<button type="submit" class="btn btn-primary">Create</button>
					</td>
				</tr>

			</table>
		</form>
		
	</div>
	
	<div class='right-button-margin'>
		<a href="admin_orders.php" class='btn btn-default pull-right'>Back</a>
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
