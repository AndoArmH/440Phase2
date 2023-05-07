<?php
// session is a variable that can be accessed from by any page
// superglobal variable
session_start();

// we put user id on session on every page to make sure
// its the same user on every page and theyre logged in

include("connection.php");
include("functions.php");

$user_data = check_login($con);
// function that will take user data and check if theyre logged in
// $con is connection to database;

// Fetch the most expensive item in each category
$sql = "SELECT * FROM items WHERE (category, price) IN (SELECT category, MAX(price) FROM items GROUP BY category)";
$result = $con->query($sql);

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
    table {
			border-collapse: collapse;
			width: 100%;
			margin: 20px 0;
			font-size: 1em;
			min-width: 400px;
			border-radius: 5px 5px 0 0;
			overflow: hidden;
			box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
		}

		table thead tr {
			background-color: #009879;
			color: #ffffff;
			text-align: left;
			font-weight: bold;
		}

		table th,
		table td {
			padding: 12px 15px;
            text-align: center;
		}

		table tbody tr {
			border-bottom: 1px solid #dddddd;
		}

		table tbody tr:nth-of-type(even) {
			background-color: #f3f3f3;
		}

		table tbody tr:last-of-type {
			border-bottom: 2px solid #009879;
		}

</style>

<body>
	<br><button class="logout-button" onclick="logout()">Logout</button>

	<?php if ($result->num_rows > 0) { ?>

		<table>
			<tr>
				<th>Category</th>
				<th>Item</th>
				<th>Price</th>
			</tr>

			<?php while ($row = $result->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row["category"]; ?></td>
					<td><?php echo $row["title"]; ?></td>
					<td><?php echo $row["price"]; ?></td>
				</tr>
			<?php } ?>

		</table>

	<?php } else { ?>

		<p>No results found.</p>

	<?php } ?>

   <br> <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

<br><br>
	<?php
	date_default_timezone_set('America/Los_Angeles');
	$date = date('Y-m-d h:i:s', time());
	echo "$date";
	?>


</body>

</html>
