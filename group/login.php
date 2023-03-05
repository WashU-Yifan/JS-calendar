<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
session_start();
require 'database.php';
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $mysqli->real_escape_string((string)$json_obj['username']);
$password = htmlentities($json_obj['password']);
$token= $json_obj['token'];
if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "CRSF detected"
	));
	exit;
}
// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*),userid, hashed_password FROM users WHERE username = ?");

// Bind the parameter
//check for SQL injection& filter input

if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}

$username = $mysqli->real_escape_string($username);
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id,$pwd_hash);
$stmt->fetch();
//$stmt->close();
// Compare the submitted password to the actual password hashe

if( $cnt==1 && password_verify($password, $pwd_hash)){
	// Login succeeded!

	$_SESSION['userid'] = $user_id;
	

	echo json_encode(array(
		"success" => true
	));
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}


?>
