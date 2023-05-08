<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = check_login($con);

if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT r.id, i.user
        FROM reviews r
        INNER JOIN items i ON r.item_id = i.id
        WHERE r.rating = 4
        GROUP BY r.id, i.user
        HAVING COUNT(*) >= 2";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    $user_pairs = array();

    while($row = $result->fetch_assoc()) {
        $user1 = $row["id"];
        $user2 = $row["user"];
        $pair = array($user1, $user2);
        
        if (!in_array($pair, $user_pairs) && !in_array(array($user2, $user1), $user_pairs)) {
            $user_pairs[] = $pair;

        echo "User Pair: (" . $user1 . " , " . $user2 . ") <br>";
    }
  }
} else {
    echo "No results found.";
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
