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

// SQL query to select users who never gave a "1" rating review to an item
$sql = "SELECT DISTINCT id FROM reviews WHERE rating <> '1'";

$result = $con->query($sql);

// Display results
if ($result->num_rows > 0) {
  echo "<div style='text-align:center;'>";
  echo "<table><tr><th>Users who never posted a poor review</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["id"]."</td></tr>";
  }
  echo "</table>";
  echo "</div>";
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
  <br><button class="logout-button" onclick="logout()">Logout</button>
  <br><br>
  <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

</body>

</html>
