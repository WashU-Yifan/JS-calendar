<?php
// login.php
session_start();
require 'database.php';
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $mysqli->real_escape_string((string)$json_obj['username']);
$pwd_guess =  $json_obj['password'];
$token=$json_obj['token'];
//This is equivalent to what you previously did with $_POST['username'] and $_POST['password']

// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)

//detect CSRF attack
if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "CRSF detected"
	));
	exit;
}

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*),id, hashed_password FROM users WHERE username = ?");


// Bind the parameter


if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo json_encode(array(
		"success" => false,
		"message" => "invalid username"
	));
	exit;

}
$_SESSION['username']=$username;

$stmt->bind_param('s', $username);

$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id,$pwd_hash);
$stmt->fetch();
$stmt->close();

// Compare the submitted password to the actual password hashe

if( $cnt==1 && password_verify($pwd_guess, $pwd_hash)){
	
	$_SESSION['username'] = $username;
    $_SESSION['user_id'] = $user_id;

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