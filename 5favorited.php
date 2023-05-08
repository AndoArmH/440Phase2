<?php

session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}
if (isset($_POST['submit'])) {

    $userX = $_POST['userX'];
    $userY = $_POST['userY'];

    if (!empty($userX) && !empty($userY)) {

        $sql = "SELECT id, COUNT(*) AS cnt FROM favorites WHERE fav_by = '$userX' OR fav_by = '$userY' GROUP BY id HAVING cnt = 2";

        // Execute the query
        $result = $con->query($sql);
        
        // Check if there are any results
        if ($result->num_rows > 0) {
            // Output the results
            while ($row = $result->fetch_assoc()) {
                echo "The User " . $row["id"] . " is favorited by both users X and Y. <br>";
            }
        } else {
            echo "No users are favorited by both users X and Y.";
        }
        
} else {
    echo "<p>Please select both users.</p>";
}

}
mysqli_close($con);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Favorites</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  <style>
    table {
      border-collapse: collapse;
      width: 50%;
      margin: 0 auto;
    }

    th,
    td {
      text-align: center;
      padding: 10px;
      border: 1px solid black;
    }

    th {
      background-color: #f2f2f2;
    }
    .logout-button {
  background-color: #f00;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}
body {
  font-family: Arial, sans-serif;
  background-color: #f2f2f2;
}
  </style>
</head>
<body>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<label for="userX">User X:</label>
		<select id="userX" name="userX">
			<option value="">Select User X</option>
			<option value="user1">User 1</option>
			<option value="user2">User 2</option>
            <option value="user3">User3</option>


		</select>

		<label for="userY">User Y:</label>
		<select id="userY" name="userY">
			<option value="">Select User Y</option>
			<option value="user1">User 1</option>
			<option value="user2">User 2</option>
            <option value="user3">User3</option>

		</select>

		<button type="submit" name="submit">Find Common Favorites</button>
	</form>
    <br><button class="logout-button" onclick="logout()">Logout</button>
  <br><br>
  <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

</body>
</html>