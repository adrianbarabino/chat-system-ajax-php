<!doctype html>
<html lang="en">
<link rel="stylesheet" href="style.css" />
<head>
	<meta charset="UTF-8">
	<title>Admin Panel</title>
</head>
<body class="index">
	<h1>This area is restringed!</h1>
	<p>Please log-in or register!</p>
	<article id="register">
	<h3>Register</h3>
	<?php require("./register.php"); ?>
	</article>

	<article id="login">
	<h3>Log in</h3>
	<?php require("./login.php"); ?>
	</article>
	
</body>
</html>