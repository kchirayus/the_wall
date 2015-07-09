<?php
	session_start();
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>Login & Registration</title>
	<link rel="stylesheet" href="css/main.css"/>
</head>
<body>
	<div id="wrapper">
<?php
	if(isset($_SESSION['errors']))
	{
		foreach($_SESSION['errors'] as $error)
		{ 
?>
		<p class="error"><?=$error?></p>
<?php
		}
		unset($_SESSION['errors']);
	}
	if(!empty($_SESSION['success']))
	{
?>
		<p class="green"><?=$_SESSION['success']?></p>
<?php
			
	}
	unset($_SESSION['success']);
?>
		<form id="login" action="process.php" method="post">
			<fieldset>
				<legend><h2>Log In</h2></legend>
				<label>Email:</label><input type="text" name="email" placeholder="Email">
				<label>Password:</label><input type="password" name="password" placeholder="Password">
				<input type="hidden" name="action" value="login">
				<input class="button" type="submit" value="Log in"/>
				<!-- <input class="button" type="submit" value="Forgot password?"/> -->
			</fieldset>
		</form>
		<form id="registration" action="process.php" method="post">
			<fieldset>
				<legend><h2>Register</h2></legend>
				<label>First name:</label><input type="text" name="first_name" placeholder="First name">
				<label>Last name:</label><input type="text" name="last_name" placeholder="Last name">
				<label>Email:</label><input type="text" name="email" placeholder="Email">
				<label>Password:</label><input type="password" name="password" placeholder="Password">
				<label>Confirm Password:</label><input type="password" name="confirm_password" placeholder="Retype your password">
				<input type="hidden" name="action" value="register">
				<input class="button" type="submit" value="Register" />
			</fieldset>
		</form>
	</div>
</body>
</html>