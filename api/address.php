<?php
// required headers
header("Access-Control-Allow-Origin: *");

//database config file and class
include_once 'config/database.php';
include_once 'config/core.php';
include_once 'classes/address.php';
require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;

// instantiate database and address object
$database = new Database();
$db = $database->connect();
  
$address = new Address($db);

if ($_SERVER['REQUEST_METHOD'] =='GET') {
	$data = $_GET;
} else if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$data = $_POST;
} else {
	$data=array();
}

$address_id = isset($data['address_id']) ? $data['address_id'] : '';
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

		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
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

