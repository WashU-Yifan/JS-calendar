<?php
require 'database.php';
session_start();
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$date=$json_obj['date'];
if(!$_SESSION['userid']){
    echo json_encode(array(
		"success" => false,
		"message" => "not logged in"
	));
	exit;
}
$userid=$_SESSION['userid'];

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT event_title, event_descript 
FROM events WHERE time=? and userid=?");

$stmt->bind_param('sd', $date,$userid);
$stmt->execute();

// Bind the results
$stmt->bind_result($title, $descript);
$stmt->fetch();
$stmt->close();
// Compare the submitted password to the actual password hash

if( $cnt<1 ){
	$password_hash= password_hash($_POST['password'], PASSWORD_BCRYPT);
   
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