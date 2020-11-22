<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//database config file and class
include_once 'config/database.php';
include_once 'classes/title.php';
  
// instantiate database and title object
$database = new Database();
$db = $database->connect();
  
$title = new Title($db);
$title_id = isset($_GET['title_id']) ? $_GET['title_id'] : '';
$request = isset($_GET['request']) ? $_GET['request'] : '';
$output=array();

$decoded_data = json_decode(file_get_contents('php://input')); 

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

		  //http_response_code(200);
		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  //http_response_code(404);
		  echo json_encode(
		      array("status"=>"error", "message" => "No titles found.")
		  );
		}
		break;
	case "add":
	  $title->name = $decoded_data->name ? $decoded_data->name : '';
	  $title->created = date('Y-m-d H:i:s');

	  if ($title->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Title added";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error adding title";
	  }

	  echo json_encode($output);
		break;	
}

