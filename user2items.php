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


// Check if search form is submitted
if(isset($_POST['search'])) {
    // Get category values
    $category1 = $_POST['category1'];
    $category2 = $_POST['category2'];
  
    // Fetch users who posted at least two items that are posted on the same day, one has a category of X, and another has a category of Y
$sql = "SELECT user FROM items WHERE category = '$category1' AND created_at IN (SELECT created_at FROM items WHERE category = '$category2' GROUP BY created_at HAVING COUNT(DISTINCT user) > 1) UNION SELECT user FROM items WHERE category = '$category2' AND created_at IN (SELECT created_at FROM items WHERE category = '$category1' GROUP BY created_at HAVING COUNT(DISTINCT user) > 1)";
$result = $con->query($sql);
  
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
    <form method="post">
    <h3>Search for users who posted at least two items that are posted on the same day, one has a category of X, and another has a category of Y</h3>
  <label for="category1">Category 1:</label>
  <input type="text" id="category1" name="category1" required><br><br>
  <label for="category2">Category 2:</label>
  <input type="text" id="category2" name="category2" required><br><br>
  <input type="submit" name="search" value="Search">
</form>
<?php if (!empty($search_results)) : ?>
		<table style="margin:auto;" cellspacing="0" cellpadding="0">

			<tr>
				<th style="padding: 10px;">User</th>
			</tr>

			<?php foreach ($search_results as $result) : ?>
				<tr class="row" id="<?php echo $result['user'] ?>" onclick="">
					<td style="padding: 10px;"><?php echo $result['user']; ?></td>
					
				</tr>
			<?php endforeach; ?>

		</table>
	<?php endif; ?>

	

   <br> <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

<br><br>
	<?php
	date_default_timezone_set('America/Los_Angeles');
	$date = date('Y-m-d h:i:s', time());
	echo "$date";
	?>


</body>

</html>
