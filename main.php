<?php
//session is a variable that can be accessed from by any page
//superglobal variable
session_start();

//we put user id on session on every page to make sure
//its the same user on every page and theyre logged in

include("connection.php");
include("functions.php");

$user_data = check_login($con);
//function that will take user data and check if theyre logged in
//$con is connection to database

date_default_timezone_set('America/Los_Angeles');
$date = date('Y-m-d h:i:s', time());
echo "$date";

// if ($_SERVER['REQUEST_METHOD'] == "POST") {
// 	$users_json = file_get_contents('init.json');
// 	$decoded_json = json_decode($users_json, true);
// 	$users = $decoded_json['tuples'];

// 	mysqli_query($con, "DELETE FROM user");

// 	foreach($users as $user) {
// 		$username = $user['username'];
// 		$password = $user['password'];
// 		$firstName = $user['firstName'];
// 		$lastName = $user['lastName'];
// 		$email = $user['email'];

// 		$salted = "dfjhg584967y98ehg75498y" . $password . "fdsjghiuo54jyi";
// 		$hashed = hash('sha512', $salted);
// 		$query = "insert into user (username,password,firstName,lastName,email) values ('$username','$hashed','$firstName','$lastName','$email')";
// 		//save into db
// 		mysqli_query($con, $query);
// 	}

// 	echo "Database reinitialized";
// 	die;
// }

// Handle the form submission when a user adds an item
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {

	// Check if the user has already posted 3 items today
	$Date = date('Y-m-d');
	$userid = $user_data['username'];
	$posted = checkUserPostCount($Date, $userid, $con);

	if($posted == '1'){
		echo '<script>alert("Sorry, you have already posted 3 items today.");</script>';
	}else if($posted == '0'){
		$title = mysqli_real_escape_string($con, $_POST['title']);
	$description = mysqli_real_escape_string($con, $_POST['description']);
	$category = mysqli_real_escape_string($con, $_POST['category']);
	$price = floatval($_POST['price']);
	

	$insert_query = "INSERT INTO items (title, description, category, price, user, created_at) VALUES ('$title', '$description', '$category', $price, '$userid', '$Date')";
	mysqli_query($con, $insert_query);

	echo '<script>alert("Item added successfully.");</script>';
	}
	
}


//Searching for item by category
$search_results = array();

//check if something has been posted with tag 'search', apply to variable category
//query search for DB items where category is the same as what the user entered
//if theres more than 1 result, display in the html by sending it to search_results array


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['search'])) {
	$category = mysqli_real_escape_string($con, $_POST['category']);

	$query = "SELECT * FROM items WHERE category LIKE '%$category%'";
	$result = mysqli_query($con, $query);

	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
			$search_results[] = $row;
		}
	}
}

?>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
.logout-button {
  background-color: #f00;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}
</style>

<body>
<br><button class="logout-button" onclick="logout()">Logout</button>

<script>
function logout() {
  window.location.href = 'logout.php';
}
</script>
	<h2>Home Page</h2>
	<br><br>

	<form method="POST">
		<h3>Search an Item for Sale</h3>
		<input type="text" name="category" placeholder="Enter category">
		<input type="submit" name="search" value="Search">
	</form>

	<?php if (!empty($search_results)): ?>
		<table style="margin:auto;">
  <tr>
    <th style="padding: 10px;">Name</th>
    <th style="padding: 10px;">Description</th>
    <th style="padding: 10px;">Category</th>
    <th style="padding: 10px;">Price</th>
  </tr>
  <?php foreach ($search_results as $result): ?>
    <tr>
      <td style="padding: 10px;"><?php echo $result['title']; ?></td>
      <td style="padding: 10px;"><?php echo $result['description']; ?></td>
      <td style="padding: 10px;"><?php echo $result['category']; ?></td>
      <td style="padding: 10px;"><?php echo '$'.$result['price']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>
	<?php endif; ?>


	<form method="POST">
	<h3>Add an Item for Sale</h3>
	<label for="title">Title:</label>
	<input type="text" id="title" name="title"><br>

	<label for="description">Description:</label>
	<textarea id="description" name="description"></textarea><br>

	<label for="category">Category:</label>
	<input type="text" id="category" name="category"><br>

	<label for="price">Price:</label>
	<input type="number" id="price" name="price" step="0.01"><br><br>

	<input type="submit" name="add" value="add">
</form>
</body>

</html>