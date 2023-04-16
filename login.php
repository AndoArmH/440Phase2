<?php
session_start();

include("connection.php");
include("functions.php");

//check to see if user clicked sign up button
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['username'])) {
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
} else 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$users_json = file_get_contents('init.json');
	$decoded_json = json_decode($users_json, true);
	$users = $decoded_json['users'];
	$items = $decoded_json['items'];
	$reviews = $decoded_json['reviews'];

	mysqli_query($con, "DROP TABLE IF EXISTS `user`");

	mysqli_query($con, 	"CREATE TABLE IF NOT EXISTS `user` (
		`username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`firstName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`lastName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

	foreach ($users as $user) {
		$username = $user['username'];
		$password = $user['password'];
		$firstName = $user['firstName'];
		$lastName = $user['lastName'];
		$email = $user['email'];

		$salted = "dfjhg584967y98ehg75498y" . $password . "fdsjghiuo54jyi";
		$hashed = hash('sha512', $salted);
		$query = "insert into user (username,password,firstName,lastName,email) values ('$username','$hashed','$firstName','$lastName','$email')";
		//save into db
		mysqli_query($con, $query);
	}

	mysqli_query($con, "DROP TABLE IF EXISTS `items`");

	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `items` (
		`id` int UNSIGNED NOT NULL AUTO_INCREMENT,
		`title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`price` decimal(10,2) NOT NULL,
		`user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`created_at` date NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

	foreach ($items as $item) {
		$id = $item['id'];
		$title = $item['title'];
		$description = $item['description'];
		$category = $item['category'];
		$price = $item['price'];
		$user = $item['user'];
		$created_at = $item['created_at'];

		$query = "insert into items (id,title,description,category,price,user,created_at) 
		values ('$id','$title','$description','$category','$price','$user','$created_at')";
		//save into db
		mysqli_query($con, $query);
	}

	mysqli_query($con, "DROP TABLE IF EXISTS `reviews`");

	mysqli_query($con, "CREATE TABLE IF NOT EXISTS `reviews` (
		`item_id` int NOT NULL,
		`id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
		`rating` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
		`description` text COLLATE utf8mb4_general_ci NOT NULL,
		`created_at` date NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

	foreach ($reviews as $r) {
		$item_id = $r['item_id'];
		$id = $r['id'];
		$rating = $r['rating'];
		$description = $r['description'];
		$created_at = $r['created_at'];

		$query = "insert into reviews (item_id,id,rating,description,created_at) 
		values ('$item_id','$id','$rating','$description','$created_at')";
		//save into db
		mysqli_query($con, $query);
	}

	echo '<script>alert("Database Reinitialized");</script>';
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

		<form method="post">
			<input type="submit" value="Initialize Database"><br><br>
		</form>

	</div>

</body>

</html>