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

$sql = "SELECT user, COUNT(*) as total_items FROM items WHERE created_at >= '2020-05-01' GROUP BY user ORDER BY total_items DESC";

$result = $con->query($sql);

// Display results
if ($result->num_rows > 0) {
  echo "<table><tr><th>Username</th><th>Total Items Posted Since 5/1/2020</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["user"]."</td><td>".$row["total_items"]."</td></tr>";
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
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<style>
    table {
      border-collapse: collapse;
      width: 50%;
      margin: auto;
    }

    th, td {
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even){background-color: #f2f2f2}

    th {
      background-color: #4CAF50;
      color: white;
    }
  </style>

<body>
  <br><button class="logout-button" onclick="logout()">Logout</button>
  <br><br>
  <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

</body>

</html>
