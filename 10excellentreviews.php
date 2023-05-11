<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT DISTINCT r1.id as userA, r2.id as userB
FROM reviews r1
JOIN reviews r2 ON r1.item_id = r2.item_id AND r1.id <> r2.id
JOIN items i1 ON r1.item_id = i1.id
JOIN items i2 ON r2.item_id = i2.id
WHERE r1.rating = '4' AND r2.rating = '4'
GROUP BY userA, userB
HAVING COUNT(i1.id) = (SELECT COUNT(*) FROM items WHERE user = userA)
  AND COUNT(i2.id) = (SELECT COUNT(*) FROM items WHERE user = userB)
";


$result = $con->query($sql);

// Display results
if ($result->num_rows > 0) {
  echo "<table><tr><th>User A</th><th>User B</th></tr>";
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row["userA"]."</td><td>".$row["userB"]."</td></tr>";
  }
  echo "</table>";
} else {
  echo "No results found";
}

$con->close();
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
  <br><button class="logout-button" onclick="logout()">Logout</button>
  <br><br>
  <a href="main.php" style="font-size: 24px; font-weight: bold;">Click Me To Go Back To Home Page</a>

</body>

</html>
