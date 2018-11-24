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
		require_once("../../config/orders.php");

		$database = new Database();
		$conn = $database->getConnection();

		$order = new Orders($conn);
	
		$order->readOne($id);

	?>
	
	<div class="table-responsive" style="width:80%;">
		
		<table class='table table-hover table-responsive table-bordered' style="width: 100%">

			<tr>
				<td style="width: 30%;">Vendor</td>
				<td style="width: 40%;"><?php echo($order->vendor); ?></td>
			</tr>

			<tr>
				<td style="width: 30%;">Start Date</td>
				<td><?php echo($order->start_date); ?></td>
			</tr>

			<tr>
				<td style="width: 30%;">End Date</td>
				<td><?php echo($order->end_date); ?></td>
			</tr>

			<tr>
				<td style="width: 30%;">Product</td>
				<td>
					<table class='table table-hover table-responsive' style="width: 100%;">
						<tr>
							<th>Name</th>
							<th>Qty Req</th>
							<th>Qty Made</th>
						</tr>
						
						<?php						
							foreach($order->fp as $f)
							{
								echo("<tr>");
									echo("<td>". $f[0]. "</td>");
									echo("<td>". $f[1]. "</td>");
									echo("<td>". $f[2]. "</td>");
								echo("</tr>");
							}
						?>
					</table>
				</td>
			</tr>

		</table>
		
	</div>
	
	<div class='right-button-margin'>
		<a href="admin_orders.php" class='btn btn-default pull-right'>Back</a>
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>
	
</html>
