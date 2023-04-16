<?php

function check_login($con)
{
	//checking to see if the user id is in db and if session is 
	//logged in thru this user id
	if (isset($_SESSION['username'])) {
		$id = $_SESSION['username'];
		$query = "select * from user where username = '$id' limit 1";

		$result = mysqli_query($con, $query);
		if ($result && mysqli_num_rows($result) > 0) {
			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	} //end if

	//redirect to login if fails 
	header("Location: login.php");
	die;
} //close check_login

function checkUsernameEmailExist($username, $email, $con)
{
	$query = "SELECT * FROM user WHERE username='$username' OR email='$email'";
	$result = mysqli_query($con, $query);
	if (mysqli_num_rows($result) > 0) {
		echo "Username or email already exists.";
		return 1;
	}
}

function checkUserPostCount($date, $userid, $con){
	// Check if the user has already posted 3 items today

$count_query = "SELECT COUNT(*) FROM items WHERE user = '$userid' AND created_at = '$date'";
$count_result = mysqli_query($con, $count_query);
$count = mysqli_fetch_array($count_result)[0];

if ($count >= 3) {
	//posted 3 times
	return 1;
}else{
	return 0;
}
}

function checkUserReviewCount($date, $userid, $con){
	// Check if the user has already posted 3 items today

$count_query = "SELECT COUNT(*) FROM reviews WHERE id = '$userid' AND created_at = '$date'";
$count_result = mysqli_query($con, $count_query);
$count = mysqli_fetch_array($count_result)[0];

if ($count >= 3) {
	//posted 3 times
	return 1;
}else{
	return 0;
}
}
