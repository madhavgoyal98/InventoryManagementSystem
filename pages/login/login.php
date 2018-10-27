<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Footer-Basic.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
	
<?php
	require_once("../../config/database.php");
	require_once("../../config/users.php");
	require_once("../../config/input_cleaning.php");
	
	$err = "";
	$database = new Database();
	$conn = $database->getConnection();
	
	if(isset($_POST['submit']))
	{
		$username = sanitizeMySQL($conn, $_POST['username']);
		$password = sanitizeMySQL($conn, $_POST['password']);
		
		$user = new Users($conn, $username, $password);
		
		$result = $user->authenticate();
		
		if($result[0] == "0")
		{
			$err = "Invalid username/password";
		}
		else
		{
			session_start();
			$_SESSION['role'] = $result[1];
			$_SESSION['username'] = $username;
			
			if($result[1] == "admin")
			{
				header('Location: ../admin_manage_users/admin_manage_users.php');
			}
			else
			{
				header('Location: ../worker_orders/worker_orders.php');
			}
		}
	}
?>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean-search">
        <div class="container"><a class="navbar-brand" href="#">ABC</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav"></ul>
                <form class="form-inline mr-auto" target="_self">
                    <div class="form-group"><label for="search-field"></label></div>
                </form>
            </div>
        </div>
    </nav>
    <div class="login-clean" style="background-image:url(&quot;assets/img/invent.jpg&quot;);background-position:0% 0%;background-repeat:round;">
        <form method="post" action="login.php">
            <h2 class="sr-only">Login Form</h2>
            <div class="illustration" style="background-image:url(&quot;assets/img/invent1.png&quot;);height:120px;background-repeat:no-repeat;"></div>
            <span id="error" style="color: red"><?php echo($err); ?></span>
            <div class="form-group">
				<input class="form-control" type="text" name="username" placeholder="Username" onClick="document.getElementById('error').innerHTML='';" required>
			</div>
            <div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Password" onClick="document.getElementById('error').innerHTML='';" required>
			</div>
            <div class="form-group">
				<button class="btn btn-primary btn-block" type="submit" name="submit">Log In</button>
			</div>
		</form>
    </div>
    
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>