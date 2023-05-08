<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);

$query = "SELECT u.username, u.firstName, u.lastName, u.email
FROM user u
LEFT JOIN reviews r ON u.username = r.id
WHERE r.rating != 1 OR r.rating IS NULL
GROUP BY u.username
HAVING COUNT(r.id) > 0;
";

$result = $con->query($query);
if (!$result) {
    echo 'Failed to execute query: ' . $con->error;
    exit();
  }
echo '<table>';
echo '<tr><th>Username</th><th>Name</th><th>Email</th></tr>';
while ($row = $result->fetch_assoc()) {
  echo '<tr>';
  echo '<td>' . $row['username'] . '</td>';
  echo '<td>' . $row['firstName'] . ' ' . $row['lastName'] . '</td>';
  echo '<td>' . $row['email'] . '</td>';
  echo '</tr>';
}
echo '</table>';

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