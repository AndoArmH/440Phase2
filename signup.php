<?php

session_start();

include("connection.php");
include("functions.php");

//check to see if user clicked sign up button
if ($_SERVER['REQUEST_METHOD'] == "POST") {

	//user clicked sign up, check credentials
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = $_POST['email'];

	if ($password != $confirm) {
		echo "Passwords do not match.";
		exit;
	}

	if (!empty($username) && !empty($password) && !empty($firstName) && !empty($lastName) && !empty($email)) {

		$unResult = mysqli_query($con, "SELECT username FROM user WHERE username='$username'");
		if (mysqli_num_rows($unResult) > 0) {
			echo "Username taken.";
			exit;
		}

		$emResult = mysqli_query($con, "SELECT email FROM user WHERE email='$email'");
		if (mysqli_num_rows($emResult) > 0) {
			echo "Email already in use.";
			exit;
		}

		//save to db
		$salted = "dfjhg584967y98ehg75498y" . $password . "fdsjghiuo54jyi";
		$hashed = hash('sha512', $salted);
		$query = "insert into user (username,password,firstName,lastName,email) values ('$username','$hashed','$firstName','$lastName','$email')";
		//save into db
		mysqli_query($con, $query);

		//redirect user to login
		header("Location: login.php");
		die;
	} else {
		echo "Please enter ALL fields";
	}
} //end if


?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign up</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

	<div>
		<form method="POST">
			<h2>Sign up</h2>


			<label for="username">Username:</label>
			<input type="text" name="username"><br><br>

			<p id="badUsername" style="color:red;"></p>

			<label for="password">Password:</label>
			<input type="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*?[#?!@$%^&*-\]\[]).{8,}"
				title="The password must be 8 characters, at least one letter, one number, and one special character."><br><br>

			<label for="confirm">Confirm Password:</label>
			<input type="password" id="confirm" name="confirm" pattern="(?=.*\d)(?=.*[a-z])(?=.*?[#?!@$%^&*-\]\[]).{8,}"
				title="The password must be 8 characters, at least one letter, one number, and one special character."><br><br>

			<p style="color:red;">The password must be 8 characters, at least one letter, one number, and one special
				character.</p>

			<label for="firstName">First Name:</label>
			<input type="text" name="firstName"><br><br>

			<label for="lastName">Last Name:</label>
			<input type="text" name="lastName"><br><br>

			<label for="email">email:</label>
			<input type="text" name="email"><br><br>

			<p id="badEmail" style="color:red;"></p>

			<input type="submit" value="Sign Up"><br><br>

			<a href="login.php">Already have an account? Log in</a><br><br>

		</form>

	</div>

</body>

</html>