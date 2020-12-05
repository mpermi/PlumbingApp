<?php
// required headers
header("Access-Control-Allow-Origin: *");

//database config file and class
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'classes/title.php';
require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;

// instantiate database and title object
$database = new Database();
$db = $database->connect();
  
$title = new Title($db);

if ($_SERVER['REQUEST_METHOD'] =='GET') {
	$data = $_GET;
} else if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$data = $_POST;
} else {
	$data=array();
}

$title_id = isset($data['title_id']) ? $data['title_id'] : '';
$token = isset($data['token']) ? $data['token'] : '';
$request = isset($data['request']) ? $data['request'] : '';
$output=array();

//validate login token
try {
	$decoded_token = JWT::decode($token, $key, array('HS256'));
} catch (Exception $e) {
  echo json_encode(array("status" => "error", "data" => "Authentication failed. " . $e->getMessage()));
  return;
}

switch ($request) {
	case "find":
		// query titles
		$titles = $title->get_all($title_id);
		$total = $titles->rowCount();
		  
		if($total) {
		  while ($row = $titles->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
		        "title_id" => $title_id,
		        "name" => $name
		    );

		    $output["data"][] = $result;
		  }

		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  echo json_encode(
		      array("status"=>"error", "message" => "No titles found.")
		  );
		}
		break;
	case "add":
	  $title->name = $data['name'] ? $data['name'] : '';
	  $title->created = date('Y-m-d H:i:s');

	  if ($title->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Title added";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error adding title";
	  }

	  echo json_encode($output);
		break;	
}

