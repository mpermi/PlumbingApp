<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//database config file and class
include_once 'config/database.php';
include_once 'classes/address.php';
  
// instantiate database and address object
$database = new Database();
$db = $database->connect();
  
$address = new Address($db);
$address_id = isset($_GET['address_id']) ? $_GET['address_id'] : '';
$request = isset($_GET['request']) ? $_GET['request'] : '';
$output=array();

$decoded_data = json_decode(file_get_contents('php://input')); 

switch ($request) {
	case "find":
		// query addresss
		$addresss = $address->get_all($address_id);
		$total = $addresss->rowCount();
		  
		if($total) {
		  while ($row = $addresss->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "address_id" => $address_id,
	        "address1" => $address1,
	        "address2" => $address2,
	        "city" => $city,
	        "state" => $state,
	        "zipcode" => $zipcode
		    );

		    $output["data"][] = $result;
		  }

		  //http_response_code(200);
		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  //http_response_code(404);
		  echo json_encode(
		      array("status"=>"error", "message" => "No addresss found.")
		  );
		}
		break;
	case "add":
	  $address->address1 = $decoded_data->address1 ? $decoded_data->address1 : '';
	  $address->address2 = $decoded_data->address2 ? $decoded_data->address2 : '';
	  $address->city = $decoded_data->city ? $decoded_data->city : '';
	  $address->state = $decoded_data->state ? $decoded_data->state : '';
	  $address->zipcode = $decoded_data->zipcode ? $decoded_data->zipcode : '';
	  $address->created = date('Y-m-d H:i:s');

	  if ($address->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Address added";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error adding address";
	  }

	  echo json_encode($output);
		break;
	case "delete":
	  if ($address->delete($address_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Address deleted";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error deleting address";
	  }
		
	  echo json_encode($output);
		break;		
}

