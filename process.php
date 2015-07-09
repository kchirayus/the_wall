<?php 
	session_start();
	require_once('connection.php');

	// Initialize message array if it doesn't exist
	if(!isset($_SESSION['success']))
	{ 
		$_SESSION['success'] = array();
	}

	if(isset($_POST['action']))
	{
		// This is registration
		if($_POST['action'] == 'register')
		{
			register_user($_POST);
		}
		// This is login
		else if ($_POST['action'] == 'login')
		{
			login_user($_POST);
		}
		// This is posting message
		else if ($_POST['action'] == 'message')
		{
			post_message($_POST);
		}
		// This is posting comment
		else
		{
			post_comment($_POST);
		}
	}
	else
	{
		session_destroy();
		header('Location: index.php');
	}

	function register_user($post)
	{
		//--------------being of validation checks-----------------------//
		$_SESSION['errors'] = array();
		if(empty($post['first_name'])){ $_SESSION['errors'][] = "First name can't be blank"; }
		if(empty($post['last_name'])){ $_SESSION['errors'][] = "Last name can't be blank"; }
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ $_SESSION['errors'][] = "Please enter a valid email address"; }
		if(empty($post['password']) || empty($post['confirm_password'])){ $_SESSION['errors'][] = "Password field is required"; }
		if($post['password'] !== $post['confirm_password']){ $_SESSION['errors'][] = "Password must match";}
		//--------------end of validation checks-----------------------//
		if(count($_SESSION['errors']) === 0)
		{
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at)
					  VALUES ('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}', '{$_POST['password']}', NOW(), NOW())";
			run_mysql_query($query);
			$_SESSION['success'] = "Registration Successful!";
		}
		header("Location: index.php");
	}

	function login_user($post)
	{
		$query = "SELECT * FROM users WHERE users.password = '{$post['password']}'
				  AND users.email = '{$post['email']}'";
		$user = fetch($query);
		if(count($user) > 0)
		{
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['first_name'] = $user['first_name'];
			$_SESSION['logged_in'] = TRUE;
			header("Location: success.php");
		}
		else
		{
			$_SESSION['errors'][] = "Email/password you entered does not exist";
			header("Location: index.php");
		}
	}

	function post_message($post)
	{
		
		if(strlen($post['message']) < 1)
		{
			$_SESSION['errors'][] = "You cannot post an empty message!";
		}
		else
		{
			global $connection;

	    	$esc_msg = mysqli_real_escape_string($connection, $post['message']);
	    	$query = "INSERT INTO messages (message, created_at, updated_at, user_id)
	    			  VALUES ('{$esc_msg}', NOW(), NOW(), '{$_SESSION['user_id']}')";
	    	run_mysql_query($query);
		}
	    header("Location: success.php");
	}

	function post_comment($post)
	{
		if(strlen($post['comment']) < 1)
		{
			$_SESSION['comment'][] = "You cannot post an empty comment!";
		}
		else
		{
			global $connection;

	    	$esc_cmt = mysqli_real_escape_string($connection, $post['comment']);
	    	$query = "INSERT INTO comments (comment, created_at, updated_at, message_id, user_id)
	    			  VALUES ('{$esc_cmt}', NOW(), NOW(), '{$post['message_id']}', '{$_SESSION['user_id']}')";
	    	run_mysql_query($query);
		}
		// var_dump($_SESSION);
		// die();
	    header("Location: success.php");
	}
?>