<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Item</title>
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
		require_once("../../config/raw_material.php");

		$database = new Database();
		$conn = $database->getConnection();

		$im = new Intermediate($conn);
		$rm = new RawMaterial($conn);
	
		$im->readOne($id);

	?>
	
	<div class="table-responsive" style="width:80%;">
		
		<table class='table table-hover table-responsive table-bordered' style="width: 100%">

			<tr>
				<td style="width: 30%;">Name</td>
				<td style="width: 40%;"><?php echo($im->name); ?></td>
			</tr>

			<tr>
				<td style="width: 30%;">Quantity</td>
				<td><?php echo($im->quantity); ?></td>
			</tr>

			<tr>
				<td style="width: 30%;">Measuring Unit</td>
				<td><?php echo($im->measuring_unit); ?></td>
			</tr>

			<tr>
				<td style="width: 30%;">Raw material used</td>
				<td>
					<table class='table table-hover table-responsive' style="width: 100%;">
						<?php
							$keys = array_keys($im->rm_used);
						
							for($i = 0; $i < count($keys); $i++)
							{
								echo("<tr>");
									echo("<td>". $keys[$i]. "</td>");
									echo("<td style='border-right: solid 2px;'>". $im->rm_used[$keys[$i]]. "</td>");

									$i++;

									if($i < count($keys))
									{
										echo("<td>". $keys[$i]. "</td>");
										echo("<td>". $im->rm_used[$keys[$i]]. "</td>");
									}
								echo("</tr>");
							}
						?>
					</table>
				</td>
			</tr>

			<tr>
				<td style="width: 30%;">Intermediate used</td>
				<td>
					<table class='table table-hover table-responsive' style="width: 100%;">
						<?php
							$keys = array_keys($im->im_used);
						
							for($i = 0; $i < count($keys); $i++)
							{
								echo("<tr>");
									echo("<td>". $keys[$i]. "</td>");
									echo("<td style='border-right: solid 2px;'>". $im->im_used[$keys[$i]]. "</td>");

									$i++;

									if($i < count($keys))
									{
										echo("<td>". $keys[$i]. "</td>");
										echo("<td>". $im->im_used[$keys[$i]]. "</td>");
									}
								echo("</tr>");
							}
						?>
					</table>
				</td>
			</tr>

		</table>
		
	</div>
	
	<div class='right-button-margin'>
		<a href="admin_intermediate_items.php" class='btn btn-default pull-right'>Back</a>
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
