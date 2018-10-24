<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
	
<?php
	require("../db_cred.php"); //database credentials
	require("../input_cleaning.php"); //input sanitization functions
	
	$connection = new MySQLi($db_host, $db_user, $db_pass, $db_name);
	
	// page given in URL parameter, default page is one
	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	// set number of records per page
	$records_per_page = 5;

	// calculate for the query LIMIT clause
	$from_record_num = ($records_per_page * $page) - $records_per_page;
	
	$query = "SELECT * FROM categories;";
	$result = $connection->query($query);
	
	if(!$result)
	{
		die($connection->connect_error);
	}
	
	// total rows in table
	$total_rows = $result->num_rows;
	$result->close();
	
	$query = "SELECT * FROM categories ORDER BY category ASC LIMIT {$from_record_num}, {$records_per_page};";
	$result = $connection->query($query);
	
	if(!$result)
	{
		die($connection->connect_error);
	}
?>

<body style="background-color:transparent;">
	
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean" style="background-color:#0b60d6;">
            <div class="container"><a class="navbar-brand" href="#" style="color:rgb(254,254,254);">ABC</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                
				<div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item" role="presentation" style="background-color:#1773f3;"><a class="nav-link" href="#" style="color:rgb(253,253,253);">Manage Users</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="color:rgb(251,251,252);">Orders</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="color:rgb(254,254,254);">Raw Material</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="color:rgb(255,255,255);">Intermediate Items</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#" style="color:rgb(254,254,254);">Finished Items</a></li>
                    </ul>
            	</div>
				
    		</div>
    	</nav>
		
    </div>
	
	<a href="#" class="btn btn-default" style="float:right;color:rgb(2,2,2);background-color:#edeaea;margin:7% 35% 0px;border-color:black;font-size:13px;">Add Category</a>
	
	<div class="table-responsive" style="width:40%;margin:10% 25% 0px;">
			
		<?php

			// display the categories if there are any
			if($total_rows>0)
			{
				echo("<table class='table table-hover table-responsive table-bordered' style='width:100%;'>
						<thead>
							<tr style='background-color:rgba(237,234,234,0.2);'>
								<th style='width:50%;border-right:solid 1px;'>Categories</th>
								<th style='width:50%;'>Actions</th>
							</tr>
						</thead>

						<tbody>");

				while ($row = $result->fetch_array(MYSQLI_NUM))
				{
					echo("<tr>");
						echo("<td>". $row[0]. "</td>");

						echo("<td>");

							// edit category button
							echo("<a href='.php?id={$row[0]}' class='btn btn-info'>");
								echo("<span class='glyphicon glyphicon-edit'></span> Edit");
							echo("</a>");

							// delete category button
							echo("<a delete-id='{$row[0]}' class='btn btn-danger'>");
								echo("<span class='glyphicon glyphicon-remove'></span> Delete");
							echo("</a>");

						echo("</td>");
					echo("</tr>");
				}

				echo("</tbody></table>");
		?>


		<?php

				// the page where this paging is used
				$page_url = "admin_category.php?";

				// paging buttons here
				include_once('paging.php');
			}

			// tell the user there are no products
			else
			{
				echo "<div class='alert alert-info'>No products found.</div>";
			}
		?>

	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>

</html>