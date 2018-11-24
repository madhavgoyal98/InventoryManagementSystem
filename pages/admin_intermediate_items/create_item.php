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
		require_once("../../config/intermediate.php");
		require_once("../../config/input_cleaning.php");
		require_once("../../config/raw_material.php");

		$database = new Database();
		$conn = $database->getConnection();

		$im = new Intermediate($conn);
		$rm = new RawMaterial($conn);

	?>
	
	<?php
	
		// if the form was submitted
		if($_POST)
		{
			$im->name = sanitizeMySQL($conn, $_POST['name']);
			$im->quantity = sanitizeMySQL($conn, $_POST['quantity']);
			$im->measuring_unit = sanitizeMySQL($conn, $_POST['measuring']);
			
			//getting quantities of raw materials used
			$result = $rm->readAll(1, $rm->countAll());
			
			for($i = 0; $i < $result->num_rows; $i++)
			{
				$row = $result->fetch_array(MYSQLI_NUM);
				
				$im->rm_used[$row[0]] = $_POST['rm_'. $row[0]];
			}
			
			unset($result);
			unset($row);
			
			
			//getting quantities of intermediate used
			$result = $im->readAll(1, $rm->countAll());
			
			for($i = 0; $i < $result->num_rows; $i++)
			{
				$row = $result->fetch_array(MYSQLI_NUM);
				
				$im->im_used[$row[0]] = $_POST['im_'. $row[0]];
			}
			
			unset($result);
			unset($row);
			
			
			// create the item
			if($im->create())
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
					<td style="width: 30%;">Name</td>
					<td style="width: 40%;"><input type='text' name='name' class='form-control' maxlength="100" oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' required></td>
				</tr>

				<tr>
					<td style="width: 30%;">Quantity <font color=#FF0004>(enter from main page)</font></td>
					<td><input type='tel' name='quantity' value="0" class='form-control' readonly></td>
				</tr>

				<tr>
					<td style="width: 30%;">Measuring Unit</td>
					<td><input type='text' name='measuring' class='form-control' maxlength="20" oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);' required></td>
				</tr>
				
				<tr>
					<td style="width: 30%;">Raw material used <br><font color=#FF0004>(cannot be updated once submitted)</font></td>
					<td>
						<table class='table table-hover table-responsive' style="width: 100%;">
							<?php
								$result = $rm->readAll(1, $rm->countAll());
							
								for($i = 0; $i < $result->num_rows; $i++)
								{
									$row = $result->fetch_array(MYSQLI_NUM);
									
									echo("<tr>");
										echo("<td>". $row[1]. "</td>");
										echo("<td style='border-right: solid 2px;'>". "<input type='number' name='rm_$row[0]' value='0' min='0' class='form-control' maxlength='4' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
																				
										$i++;
									
										if($i < $result->num_rows)
										{
											$row = $result->fetch_array(MYSQLI_NUM);
											
											echo("<td>". $row[1]. "</td>");
											echo("<td>". "<input type='number' name='rm_$row[0]' value='0' min='0' class='form-control' maxlength='4' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
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
					<td style="width: 30%;">Intermediate used</td>
					<td>
						<table class='table table-hover table-responsive' style="width: 100%;">
							<?php
								$result = $im->readAll(1, $rm->countAll());
							
								for($i = 0; $i < $result->num_rows; $i++)
								{
									$row = $result->fetch_array(MYSQLI_NUM);
									
									echo("<tr>");
										echo("<td>". $row[1]. "</td>");
										echo("<td style='border-right: solid 2px;'>". "<input type='number' name='im_$row[0]' value='0' class='form-control' maxlength='4' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
																				
										$i++;
									
										if($i < $result->num_rows)
										{
											$row = $result->fetch_array(MYSQLI_NUM);
											
											echo("<td>". $row[1]. "</td>");
											echo("<td>". "<input type='number' name='im_$row[0]' value='0' class='form-control' maxlength='4' oninput='javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);'>". "</td>");
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
		<a href="admin_intermediate_items.php" class='btn btn-default pull-right'>Back</a>
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
