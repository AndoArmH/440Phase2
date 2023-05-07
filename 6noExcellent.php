<?php
// session is a variable that can be accessed from any page
// superglobal variable
session_start();

// we put user id on session on every page to make sure
// its the same user on every page and theyre logged in

include("connection.php");
include("functions.php");

$user_data = check_login($con);
// function that will take user data and check if theyre logged in
// $con is connection to database;

$sql = "SELECT DISTINCT user.username
        FROM user
        LEFT JOIN items ON user.username = items.user
        LEFT JOIN reviews ON items.id = reviews.item_id
        WHERE items.id IS NULL
        OR (SELECT COUNT(*) FROM reviews WHERE reviews.item_id = items.id AND reviews.rating = 4) < 3";

$result = $con->query($sql);

// Display results
if ($result->num_rows > 0) {
  echo "<table><tr><th>Users who never had an excellent rating on their items 3 or more times</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["username"]."</td></tr>";
  }
  echo "</table>";
} else {
  echo "No results found";
}

?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Document</title>
  <style>
    table {
      border-collapse: collapse;
      width: 50%;
      margin: auto;
    }

    th,
    td {
      text-align: left;
      padding: 8px;
    }

    th {
      background-color: #343a40;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2
    }
    .logout-button {
  background-color: #f00;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}
  </style>
</head>

<body>
  <br><button class="logout-button" onclick="logout()">Logout</button>
  <br><br>
  <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

</body>

</html>
