<?php
	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])){
		header('Location: index.php');
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Refinery CMS</title>
	</head>
	<html>
		<form action="index.php" method="POST">
			<p>Username:</p>
			<input name="username" type="text" style="width: 200px;" />
			<p>Password:</p>
			<input name="password" type="password" style="width: 200px;" />
			<br />
			<input type="submit" value="Log in" />
		</form>
	</html>
</html>