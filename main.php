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
//$con is connection to database;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id'])) {
	$id = $_POST['id'];
	$revs = mysqli_query($con, "SELECT * FROM reviews WHERE item_id = " . $id);

	if ($revs) {
		// Fetch the rows from the result set and store them in an array
		$rows = array();
		while ($row = mysqli_fetch_assoc($revs)) {
			$rows[] = $row;
		}

		// Convert the array to a JSON string
		$json = json_encode($rows);

		// Output the JSON string
		echo $json;
		die;
	}
}

date_default_timezone_set('America/Los_Angeles');
$date = date('Y-m-d h:i:s', time());
echo "$date";

// Handle the form submission when a user adds an item
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add'])) {

	if (!empty($_POST['title']) && !empty($_POST['description']) && !empty($_POST['category']) && !empty($_POST['price'])) {
		$Date = date('Y-m-d');
		$userid = $user_data['username'];
		$posted = checkUserPostCount($Date, $userid, $con);

		if ($posted == '1') {
			echo '<script>alert("Sorry, you have already posted 3 items today.");</script>';
		} else if ($posted == '0') {
			$title = mysqli_real_escape_string($con, $_POST['title']);
			$description = mysqli_real_escape_string($con, $_POST['description']);
			$category = mysqli_real_escape_string($con, $_POST['category']);
			$price = floatval($_POST['price']);

			$insert_query = "INSERT INTO items (title, description, category, price, user, created_at) VALUES ('$title', '$description', '$category', $price, '$userid', '$Date')";
			mysqli_query($con, $insert_query);

			echo '<script>alert("Item added successfully.");</script>';
		}
	} else {
		echo '<script>alert("ERROR: Fill All Fields");</script>';
	}
}
// review 
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['review'])) {

	if (!empty($_POST['description'])) {
		$Date = date('Y-m-d');
		$userid = $user_data['username'];
		$posted = checkUserReviewCount($Date, $userid, $con);

		if ($posted == '1') {
			echo '<script>alert("Sorry, you have already reviewed 3 items today.");</script>';
		} else if ($posted == '0') {
			$userid = $user_data['username'];
			$itemid = mysqli_real_escape_string($con, $_POST['item_id']);
			$rating = mysqli_real_escape_string($con, $_POST['rating']);
			$description = mysqli_real_escape_string($con, $_POST['description']);

			$query = "SELECT * FROM items WHERE id = $itemid AND user = '$userid'";
			$result = mysqli_query($con, $query);
			if (mysqli_num_rows($result) > 0) {
				echo '<script>alert("Sorry, you cannot review your own item.");</script>';
			} else {
				$insert_query = "INSERT INTO reviews (item_id, id, rating, description, created_at) VALUES ($itemid, '$userid', $rating, '$description','$Date')";
				mysqli_query($con, $insert_query);

				echo '<script>alert("Review added successfully.");</script>';
			}
		}
	} else {
		echo '<script>alert("ERROR: Fill All Fields");</script>';
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

</style>

<body>
	<br><button class="logout-button" onclick="logout()">Logout</button>

	<h2>Home Page</h2>
	<br><br>
	<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
		crossorigin="anonymous"></script>

	<div id="reviews">

	</div>

	<form method="POST">
		<h3>Search an Item for Sale</h3>
		<input type="text" name="category" placeholder="Enter category">
		<input type="submit" name="search" value="Search">
	</form>

	<?php if (!empty($search_results)) : ?>
		<table style="margin:auto;">

			<tr>
				<th style="padding: 10px;">Name</th>
				<th style="padding: 10px;">Description</th>
				<th style="padding: 10px;">Category</th>
				<th style="padding: 10px;">Price</th>
				<th style="padding: 10px;">User</th>
				<th style="padding: 10px;">Review</th>
			</tr>

			<?php foreach ($search_results as $result) : ?>
				<tr class="row" id="<?php echo $result['id'] ?>" onclick="">
					<td style="padding: 10px;"><?php echo $result['title']; ?></td>
					<td style="padding: 10px;"><?php echo $result['description']; ?></td>
					<td style="padding: 10px;"><?php echo $result['category']; ?></td>
					<td style="padding: 10px;"><?php echo '$' . $result['price']; ?></td>
					<td style="padding: 10px;"><?php echo $result['user']; ?></td>
					<td style="padding: 10px;">
						<form method="POST">
							<select name="rating">
								<option value="1">Poor</option>
								<option value="2">Fair</option>
								<option value="3">Good</option>
								<option value="4">Excellent</option>
							</select>
							<input type="hidden" name="item_id" value="<?php echo $result['id']; ?>">
							<input type="text" name="description" placeholder="Enter a description">
							<input type="submit" name="review" value="Submit Review">
						</form>
					</td>
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

	<script>
		let reviews = document.getElementById('reviews');

		function logout() {
			window.location.href = 'logout.php';
		}

		function rating(n) {
			switch (n) {
				case '1':
					return 'Poor'
				case '2':
					return 'Fair'
				case '3':
					return 'Good'
				case '4':
					return 'Excellent'
			}
		}

		function loadReviews(event, id) {
			fetch('main.php', {
				method: 'POST',
				headers: {
					"Content-Type": 'application/x-www-form-urlencoded'
				},
				body: 'id=' + encodeURIComponent(id)
			}).then((res) => res.text()).then((data) => {
				let arr = JSON.parse(data);
				// console.log(arr)

				let str = ""
				str += arr.length == 0 ? "No Reviews" : "Reviews:"

				for (let i = 0; i < arr.length; i++) {
					let el = arr[i];
					str +=
						`
					<div class="review">
		<div>
			User: ${el['id']}
		</div>
		<div>
			Rating: ${rating(el['rating'])}
		</div>
		<div>
			\"${el['description']}\"
		</div>
	</div>
					`
				}

				reviews.innerHTML = str;
				reviews.style.display = 'flex'
				reviews.style.top = `${event.pageY - 40}px`;
				reviews.style.left = `0px`;
			})
		}

		$('form').on('click', function(e) {
			e.stopPropagation()
		})

		$('.row').on("click", function(e) {
			loadReviews(e, this.id)
		})

		document.addEventListener("click", function(e) {
			if (!e['keep'])
				reviews.style.display = 'none';
		})
	</script>

</body>

</html>