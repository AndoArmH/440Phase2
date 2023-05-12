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

$search_results = array();

// Check if form is submitted
if(isset($_POST['search'])) {
  // Get input value
  $username = $_POST['username'];

  // Fetch items posted by user X, with rating of 4 or 5 and not have any other ratings besides 4 or 5 by any other user
$sql = "SELECT * FROM items JOIN reviews ON items.id = reviews.item_id WHERE items.user = '$username' AND reviews.rating IN (3, 4)
AND NOT EXISTS (SELECT * FROM reviews WHERE item_id = items.id AND id != '$username' AND rating NOT IN (3, 4))";
$result = $con->query($sql);

  // Display results
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $rating = $row["rating"] == 4 ? "Excellent" : "Good";
      $search_results[] = $row;
    }
  } else {
    echo "No results found";
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

<body>
  <br><button class="logout-button" onclick="logout()">Logout</button>
  <form method="post">
  <h3>Search For Users With Excellent/Good Rated Items</h3>

    <label for="username">User Name:</label>
    <input type="text" id="username" name="username" required><br><br>
    <input type="submit" name="search" value="Search">
  </form>
  <?php if (!empty($search_results)) : ?>
		<table style="margin:auto;">

			<tr>
				<th style="padding: 10px;">Title</th>
				<th style="padding: 10px;">Description</th>
				<th style="padding: 10px;">Category</th>
				<th style="padding: 10px;">Price</th>
				<th style="padding: 10px;">Rating</th>
			</tr>

			<?php foreach ($search_results as $result) : ?>
				<tr class="row" id="" onclick="">
					<td style="padding: 10px;"><?php echo $result['title']; ?></td>
					<td style="padding: 10px;"><?php echo $result['description']; ?></td>
					<td style="padding: 10px;"><?php echo $result['category']; ?></td>
					<td style="padding: 10px;"><?php echo '$' . $result['price']; ?></td>
                    <?php $rating = $result["rating"] == 4 ? "Excellent" : "Good"; ?>
					<td style="padding: 10px;"><?php echo $rating; ?></td>
					
				</tr>
			<?php endforeach; ?>

		</table>
	<?php endif; ?>

  <br><br>
  <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

</body>

</html>
