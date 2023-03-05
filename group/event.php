<?php
ini_set("session.cookie_httponly", 1);
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
		"message" => "guest user"
	));
	exit;
}
$userid=$_SESSION['userid'];

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT event_time,event_title, event_descript FROM events WHERE event_date=? and userid=?");
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => "sql prep failed"
	));
	exit;
}
$stmt->bind_param('sd', $date,$userid);
$stmt->execute();

// Bind the results
$stmt->bind_result($time,$title, $descript);

if($stmt->fetch()){
	echo json_encode(array(
		"success" => true,
		"event"=>true,
		"time"=>$time,
		"title" => $title,
		"descript"=>$descript
	));
}
else{
	echo json_encode(array(
		"success" => true,
		"event"=>false,
		"title" => $title,
		"descript"=>$descript
	));
}
$stmt->close();
// Compare the submitted password to the actual password hash



?>