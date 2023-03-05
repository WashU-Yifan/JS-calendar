<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
require 'database.php';
session_start();
 // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $mysqli->real_escape_string((string)$json_obj['username']);
$pwd1 =  $json_obj['password1'];
$pwd2 =  $json_obj['password2'];
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
$stmt = $mysqli->prepare("SELECT COUNT(*)FROM users WHERE username=?");


//check for SQL injection& filter input

if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo json_encode(array(
		"success" => false,
		"message" => "Invalid username"
	));
	exit;

}
if($pwd1!=$pwd2){
    echo json_encode(array(
		"success" => false,
		"message" => "password does not match"
	));
	exit;
}

$username = $mysqli->real_escape_string($username);
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt);
$stmt->fetch();
$stmt->close();
// Compare the submitted password to the actual password hash

if( $cnt<1 ){
	$password_hash= password_hash($pwd1, PASSWORD_BCRYPT);
   
    $stmt = $mysqli->prepare("INSERT INTO users (username, hashed_password) VALUES (?, ?)");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "sql prep failed"
        ));
        exit;
       // printf("Query Prep Failed: %s\n", $mysqli->error);
    }

    $stmt->bind_param('ss', $username,$password_hash);

    $stmt->execute();

    $stmt->close();

	echo json_encode(array(
		"success" => true
	));
	exit;
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Username already exist"
	));
	exit;
}


?>