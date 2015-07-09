<?php
	session_start();
	require_once('connection.php');
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<title>Logged In</title>
	<link rel="stylesheet" href="css/main.css"/>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1 id="title">Welcome to the Wall</h1>
			<p id="header_name">Welcome <?=ucwords($_SESSION['first_name'])?></p>
			<a id="logoff" href="process.php">Log Off</a>
		</div>

		<form id="post_box" action="process.php" method="post">
			<h2>Post a Message</h2>
<?php
    if(!empty($_SESSION['errors']))
	{
?>
			<p class="error"><?=$_SESSION['errors'][0]?></p>
<?php
	unset($_SESSION['errors']);
	}
?>
			<textarea id="post_message" name="message"></textarea>
			<input type="hidden" name="action" value="message">
			<input class="button" type="submit" value="Post a message"/>
		</form>

		<div id="msg_box">
<?php
	if(empty($messages)){
		$messages = array();
	}

	$query = "SELECT first_name, last_name, DATE_FORMAT(messages.created_at, '%M %D %Y') AS created_at, message, messages.id 
			  FROM messages 
			  LEFT JOIN users ON messages.user_id = users.id 
			  GROUP BY messages.id ORDER BY messages.created_at DESC";
	$messages = fetch_all($query);
	if(empty($messages)){
		$messages = array();
	}
	foreach($messages as $key => $value)
	{
		$message_id = $value['id'];
?>
			<h4 class="green"><?=ucwords($value['first_name']) . " " . ucwords($value['last_name']) . " - " . $value['created_at']?></h4>
			<p class="messages"><?=$value['message']?></p>
			<div class="comment_box">
				<form class="comment_form" action="process.php" method="post">
<!-- start comment logic -->
<?php
		$query = "SELECT first_name, last_name, DATE_FORMAT(comments.created_at, '%M %D %Y') AS created_at, comment, message_id 
				  FROM comments
				  LEFT JOIN messages ON comments.message_id = messages.id 
				  LEFT JOIN users ON comments.user_id = users.id
				  WHERE message_id = '$message_id' 
				  GROUP BY comments.id ORDER BY comments.created_at ASC";
		$comments = fetch_all($query);
		foreach($comments as $key => $value)
		{
?>
			<h4 class="green"><?=ucwords($value['first_name']) . " " . ucwords($value['last_name']) . " - " . $value['created_at']?></h4>
			<p class="comments"><?=$value['comment']?></p>
<?php
	}
?>
<!-- end comment logic-->
				<h4>Post a comment</h4>
<?php
    if(!empty($_SESSION['comment']))
	{
?>
			<p class="error"><?=$_SESSION['comment'][0]?></p>
<?php
	unset($_SESSION['comment']);
	}
?>
					<textarea class="post_comment" name="comment"></textarea>
					<input type="hidden" name="action" value="comment">
					<input type="hidden" name="message_id" value=<?=$message_id?>>
					<input class="comment_button" type="submit" value="Post Comment"/>
				</form>
			</div>
<?php
	}
?>
		</div>
	</div>
</body>
</html>