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
	require("../db_cred.php"); //file for database credentials
	require("../input_cleaning.php"); //file for string santization functions
	
	$connection = new MySQLi($db_host, $db_user, $db_pass, $db_name);
	$err = "";
	
	if($connection->connect_error)
	{
		die($connection->connect_error);
	}
	
	if(isset($_POST['submit']))
	{
		//santizing input data
		$email = sanitizeMySQL($connection, $_POST['email']);
		$password = sanitizeMySQL($connection, $_POST['password']);
		$type = $_POST['memberType'];
		
		$query = "";
		
		//check if user type is 'employee' or 'student'
		if($type == "employee")
		{
			$query = "SELECT * FROM employee_login WHERE email='$email';";
		}
		else
		{
			$query = "SELECT * FROM student_login WHERE email='$email';";
		}
		
		$result = $connection->query($query);
		
		if(!$result)
		{
			die($connection->connect_error);
		}
		elseif($result->num_rows)
		{
			$row = $result->fetch_array(MYSQLI_NUM);
			$result->close();
			
			//checking account suspension status
			if($row[3] == 0)
			{
				//storing session data
				session_start();
				$_SESSION['sid'] = $row[0];
				$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
				$_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
				
				//matching the entered password with encoded password saved in database
				if(password_verify($password, $row[2]))
				{
					header('Location: ');  //link to other page
				}
				else
				{
					$err = "Invalid email/password"; //error message
				}
			}
			else
			{
				$err = "Your account has been suspended";
			}
		}
		else
		{
			$err = "Invalid email/password";
		}
		
		$connection->close();
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
				<input class="form-control" type="email" name="email" placeholder="Email" script="document.getElementById('error').innerHTML='';" required>
			</div>
            <div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Password" script="document.getElementById('error').innerHTML='';" required>
			</div>
            <div class="form-check">
				<input class="form-check-input" type="radio" id="formCheck-1" name="memberType" checked="" value="admin"><label class="form-check-label" for="formCheck-1">Admin</label>
			</div>
            <div class="form-check">
				<input class="form-check-input" type="radio" id="formCheck-2" name="memberType" value="worker"><label class="form-check-label" for="formCheck-2">Worker</label>
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