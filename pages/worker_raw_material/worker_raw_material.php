<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raw Material</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
	
<?php
	
	if($_SESSION['role'] != 'worker')
	{
		die("You don't have permission to access this page");
	}
	
	//importing class files
	require_once("../../config/database.php");
	require_once("../../config/raw_material.php");
	
	$database = new Database();
	$conn = $database->getConnection();
	
	$rm = new RawMaterial($conn);
	
	// page given in URL parameter, default page is one
	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	// set number of records per page
	$records_per_page = 5;

	// calculate for the query LIMIT clause
	$from_record_num = ($records_per_page * $page) - $records_per_page;
	
	// query products
	$result = $rm->readAll($from_record_num, $records_per_page);
	$num = $result->num_rows;
	
?>

<body style="background-color:transparent;">
	
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean" style="background-color:#0b60d6;">
            <div class="container"><a class="navbar-brand" href="#" style="color:rgb(254,254,254);">ABC</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                
				<div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        
                        
        
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../worker_orders/worker_orders.php" style="color:rgb(251,251,252);">Orders</a></li>
						<li class="nav-item" role="presentation" style="background-color:#1773f3;"><a class="nav-link" href="worker_raw_material.php" style="color:rgb(253,253,253);">Raw Material</a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="../worker_intermediate_items/worker_intermediate_items.php" style="color:rgb(255,255,255);">Intermediate Items</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../worker_finished_items/worker_finished_items.php" style="color:rgb(254,254,254);">Finished Items</a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="../logout/logout.php" style="color:rgb(254,254,254);">Logout</a></li>
                    </ul>
            	</div>
				
    		</div>
    	</nav>
		
    </div>
	
	<div class="table-responsive" style="width:45%;margin:10% 25% 0px;">
		
		<?php

			// display the item if there are any
			if($num > 0)
			{
				echo("<table class='table table-hover table-responsive table-bordered' style='width:100%;'>
						<thead>
							<tr style='background-color:rgba(237,234,234,0.2);'>
								<th style='width:35%;'>Name</th>
								<th style='width:10%;'>Quantity</th>
								<th style='width:25%;'>Measuring Unit</th>
								<th style='width:30%;'>Actions</th>
							</tr>
						</thead>

						<tbody>");

				while ($row = $result->fetch_array(MYSQLI_NUM))
				{
					echo("<tr>");
						echo("<td>". $row[1]. "</td>");
						
						echo("<td>". $row[2]. "</td>");
					
						echo("<td>". $row[3]. "</td>");

						echo("<td>");

							// edit user button
							echo("<a href='update_item.php?id={$row[0]}' class='btn btn-info'>");
								echo("<span class='glyphicon glyphicon-edit'></span> Edit");
							echo("</a>");
					
							echo("&nbsp; &nbsp; &nbsp;");

						echo("</td>");
					echo("</tr>");
				}

				echo("</tbody></table>");


				// the page where this paging is used
				$page_url = "worker_raw_material.php?";
				
				// count all items in the database to calculate total pages
				$total_rows = $rm->countAll();

				// paging buttons here
				include_once('paging.php');
			}

			// tell the admin there are no items
			else
			{
				echo "<div class='alert alert-info'>No item found.</div>";
			}
		?>
		
	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>

</html>