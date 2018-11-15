<!DOCTYPE html>

<?php
	session_start();
?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intermediate Items</title>
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
	require_once("../../config/intermediate.php");
	
	$database = new Database();
	$conn = $database->getConnection();
	
	$im = new Intermediate($conn);
	
	// page given in URL parameter, default page is one
	$page = isset($_GET['page']) ? $_GET['page'] : 1;

	// set number of records per page
	$records_per_page = 5;

	// calculate for the query LIMIT clause
	$from_record_num = ($records_per_page * $page) - $records_per_page + 1;
	
	// query products
	$result = $im->readAll($from_record_num, $records_per_page + 1);
	$num = $result->num_rows;
	
?>

<body style="background-color:transparent;">
	
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean" style="background-color:#0b60d6;">
            <div class="container"><a class="navbar-brand" href="#" style="color:rgb(254,254,254);">ABC</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                
				<div class="collapse navbar-collapse" id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        
                        
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_manage_users/admin_manage_users.php" style="color:rgb(254,254,254);">Manage Users</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_orders/admin_orders.php" style="color:rgb(251,251,252);">Orders</a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="../admin_raw_material/admin_raw_material.php" style="color:rgb(253,253,253);">Raw Material</a></li>
						<li class="nav-item" role="presentation" style="background-color:#1773f3;"><a class="nav-link" href="../admin_intermediate_items/admin_intermediate_items.php" style="color:rgb(255,255,255);">Intermediate Items</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="../admin_finished_items/admin_finished_items.php" style="color:rgb(254,254,254);">Finished Items</a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="../logout/logout.php" style="color:rgb(254,254,254);">Logout</a></li>
                    </ul>
            	</div>
				
    		</div>
    	</nav>
		
    </div>
	
	<a href="create_item.php" class="btn btn-default" style="float:right;color:rgb(2,2,2);background-color:#edeaea;margin:7% 35% 0px;border-color:black;font-size:13px;">Add Item</a>
	
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

							// delete user button
							echo("<a delete-id='{$row[0]}' class='btn btn-danger delete-object'>");
								echo("<span class='glyphicon glyphicon-remove'></span> Delete");
							echo("</a>");

						echo("</td>");
					echo("</tr>");
				}

				echo("</tbody></table>");


				// the page where this paging is used
				$page_url = "admin_intermediate_items.php?";
				
				// count all items in the database to calculate total pages
				$total_rows = $im->countAll();

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
	<script src="assets/js/bootbox.min.js"></script>
	<script>
		// JavaScript for deleting product
		$(document).on('click', '.delete-object', function(){

			var id = $(this).attr('delete-id');

			bootbox.confirm({
				message: "<h4>Are you sure?</h4>",
				buttons: {
					confirm: {
						label: '<span class="glyphicon glyphicon-ok"></span> Yes',
						className: 'btn-danger'
					},
					cancel: {
						label: '<span class="glyphicon glyphicon-remove"></span> No',
						className: 'btn-primary'
					}
				},
				callback: function (result) {

					if(result==true)
					{
						$.post('delete_item.php', {
							object_id: id
						}, function(data){
							location.reload();
						}).fail(function() {
							alert('Unable to delete.');
						});
					}
				}
			});

			return false;
		});	
	</script>
	
</body>

</html>