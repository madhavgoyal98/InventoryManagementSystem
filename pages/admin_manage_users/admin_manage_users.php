<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
	
<?php
	//page accessible only by admin
	if($_SESSION['role'] != 'admin')
	{
		die("You don't have permission to access this page");
	}
	
	//importing class files
	require_once("../../config/database.php");
	require_once("../../config/users.php");
	
	$database = new Database();
	$conn = $database->getConnection();
	
	$user = new Users($conn, "", "");
	
	// page given in URL parameter, default page is one
	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	// set number of records per page
	$records_per_page = 5;

	// calculate for the query LIMIT clause
	$from_record_num = ($records_per_page * $page) - $records_per_page;
	
	// query products
	$result = $user->readAll($from_record_num, $records_per_page);
	$num = $result->num_rows;
	
?>

<body style="background-color:transparent;">
	
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean" style="background-color:#0b60d6;">
            <div class="container"><a class="navbar-brand" href="#" style="color:rgb(254,254,254);">ABC</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                
				<div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item" role="presentation" style="background-color:#1773f3;"><a class="nav-link" href="admin_manage_users.php" style="color:rgb(253,253,253);">Manage Users</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_orders/admin_orders.php" style="color:rgb(251,251,252);">Orders</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_raw_material/admin_raw_material.php" style="color:rgb(254,254,254);">Raw Material</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_intermediate_items/admin_intermediate_items.php" style="color:rgb(255,255,255);">Intermediate Items</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_finished_items/admin_finished_items.php" style="color:rgb(254,254,254);">Finished Items</a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="../logout/logout.php" style="color:rgb(254,254,254);">Logout</a></li>
                    </ul>
            	</div>
				
    		</div>
    	</nav>
		
    </div>
	
	<a href="#" class="btn btn-default" style="float:right;color:rgb(2,2,2);background-color:#edeaea;margin:7% 35% 0px;border-color:black;font-size:13px;">Add User</a>
	
	<div class="table-responsive" style="width:40%;margin:10% 25% 0px;">
			
		<?php

			// display the users if there are any
			if($num > 0)
			{
				echo("<table class='table table-hover table-responsive table-bordered' style='width:100%;'>
						<thead>
							<tr style='background-color:rgba(237,234,234,0.2);'>
								<th style='width:50%;'>Name</th>
								<th style='width:50%;'>Role</th>
								<th style='width:50%;'>Actions</th>
							</tr>
						</thead>

						<tbody>");

				while ($row = $result->fetch_array(MYSQLI_NUM))
				{
					echo("<tr>");
						echo("<td>". $row[1]. "</td>");
						
						echo("<td>". $row[2]. "</td>");

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


				// the page where this paging is used
				$page_url = "admin_manage_users.php?";
				
				// count all products in the database to calculate total pages
				$total_rows = $user->countAll();

				// paging buttons here
				include_once('paging.php');
			}

			// tell the admin there are no users
			else
			{
				echo "<div class='alert alert-info'>No users found.</div>";
			}
		?>

	</div>
	
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	
</body>

</html>