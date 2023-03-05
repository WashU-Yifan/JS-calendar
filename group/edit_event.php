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
$title=$mysqli->real_escape_string((string)htmlentities($json_obj['title']));
$descripton=$mysqli->real_escape_string((string)($json_obj['description']));
$share_user=$mysqli->real_escape_string((string)($json_obj['share_user']));
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
    $stmt = $mysqli->prepare("UPDATE events SET event_title=?, event_descript=? WHERE eventid=?");
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
    $stmt = $mysqli->prepare("INSERT INTO events (userid, event_title,event_descript,event_time,event_date) VALUES (?,?,?,?,?)");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "sql prep failed"
        ));
        exit;
    }
    $stmt->bind_param('dssss',$userid, $title, $descripton,$time,$date);
    if($share_user){
        //we want to share this event to another user
        //first we have to fetch the userid based on the user name provided.
        $stmt = $mysqli->prepare("SELECT userid FROM users WHERE username=?"); 
        if(!$stmt){
            echo json_encode(array(
                "success" => false,
                "message" => "sql prep failed"
            ));
            exit;
        }
        $stmt->bind_param('s', $share_user);
        $stmt->execute();
        $stmt->bind_result($share_id);
        $stmt->fetch();
        $stmt->close();
        if($share_id){
            $stmt = $mysqli->prepare("INSERT INTO events ( userid,event_title,event_descript,event_time,event_date) VALUES (?,?,?,?,?)");
            if(!$stmt){
                echo json_encode(array(
                    "success" => false,
                    "message" => "sql prep failed"
                ));
                exit;
            }
            $stmt->bind_param('dssss',$shareid, $title, $descripton,$time,$date);
            $stmt->execute();
            $stmt->close();
        }
    }
    
    $stmt->execute();
    $stmt->close();
}
echo json_encode(array(
    "success" => true
));

?>