<?php
session_start();

include("connection.php");
include("functions.php");

//check to see if user clicked sign up button
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//something was posted
	$username = $_POST['username'];
	$password = $_POST['password'];


	if (!empty($username) && !empty($password)) {

		//read from database
		$query = "select * from user where username = '$username' limit 1";
		$result = mysqli_query($con, $query);

		if ($result) {
			if ($result && mysqli_num_rows($result) > 0) {

				$user_data = mysqli_fetch_assoc($result);
				$salted = "dfjhg584967y98ehg75498y" . $password . "fdsjghiuo54jyi";
				$hashed = hash('sha512', $salted);
				if ($user_data['password'] === $hashed) {

					$_SESSION['username'] = $user_data['username'];
					header("Location: main.php");
					die;
				}
			}
		}

		echo "wrong username or password!";
	} else {
		echo "wrong username or password!";
	}
}
?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

	<div>
		<form method="post">
			<h2>Login</h2>

			<label for="username">Username:</label>
			<input type="text" name="username"><br><br>
			<label for="password">Password:</label>
			<input type="password" name="password"><br><br>

			<input type="submit" value="Login"><br><br>

			<a href="signup.php">Dont have an account? Sign Up</a><br><br>

		</form>

	</div>

</body>

</html>