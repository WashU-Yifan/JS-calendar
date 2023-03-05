<?php
ini_set("session.cookie_httponly", 1);
header("Content-Type: application/json");
session_start();
require 'database.php';
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$token= $json_obj['token'];
if(!hash_equals($_SESSION['token'],$token)){
    echo json_encode(array(
		"success" => false,
		"message" => "CRSF detected"
	));
	exit;
}
$_SESSION['userid'] = 0;


echo json_encode(array(
	"success" => true
));
exit;


?>
