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
$title=$json_obj['title'];
$descripton=$json_obj['description'];
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
		"message" => "not logged in"
	));
	exit;
}
$userid=$_SESSION['userid'];

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*),eventid FROM events WHERE userid=? and time=?"); 
if(!$stmt){
    echo json_encode(array(
        "success" => false,
        "message" => "sql prep failed"
    ));
    exit;
}
$stmt->bind_param('ds', $userid, $date);
$stmt->execute();
$stmt->bind_result($cnt,$eventid);
$stmt->fetch();
$stmt->close();

if($cnt){// when there is a event in that data belongs to that user
    $stmt = $mysqli->prepare("UPDATE eventid SET event_title=?, event_descript=? WHERE eventid=?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "sql prep failed"
        ));
        exit; 
    }
    $stmt->bind_param('ssd', $title, $descripton,$eventid);
    $stmt->execute();
    $stmt->close();
}
else{
    $stmt = $mysqli->prepare("INSERT INTO events (time,userid, event_title,event_descript) VALUES (?,?,?,?)");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "sql prep failed"
        ));
        exit;
    }
    $stmt->bind_param('sdss',$date,$userid, $title, $descripton);
    $stmt->execute();
    $stmt->close();
}
echo json_encode(array(
    "success" => true
));

?>