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
$time=$json_obj['time'];
$token=$json_obj['token'];

if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "CRSF detected"
	));
	exit;
}

if(!$_SESSION['userid']){
    echo json_encode(array(
		"success" => false,
		"message" =>"not logged in"
	));
	exit;
}



$userid=$_SESSION['userid'];

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*),eventid FROM events WHERE userid=? and event_date =? and event_time=?"); 
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => "sql prep failed"
    ));
    exit;
}
$stmt->bind_param('dss', $userid, $date,$time);
$stmt->execute();
$stmt->bind_result($cnt,$eventid);
$stmt->fetch();
$stmt->close();

if($cnt){// when there is a event in that data belongs to that user
    $stmt = $mysqli->prepare("DELETE FROM events WHERE eventid=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "sql prep failed"
        ));
        exit; 
    }

    $stmt->bind_param('d', $eventid);
    $stmt->execute();
    $stmt->close();
    echo json_encode(array(
        "success" => true
    ));    
    exit;
}
else{
    echo json_encode(array(
        "success" => false,
        "message" => "event not exist"
    ));
    exit;
}


?>