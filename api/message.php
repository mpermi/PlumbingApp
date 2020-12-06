<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");  

//database config file and class
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'classes/message.php';
require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;

// instantiate database and message object
$database = new Database();
$db = $database->connect();
  
$message = new Message($db);

if ($_SERVER['REQUEST_METHOD'] =='GET') {
	$data = $_GET;
} else if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$data = $_POST;
} else {
	$data=array();
}

$message_id = isset($data['message_id']) ? $data['message_id'] : '';
$direction = isset($data['direction']) ? $data['direction'] : '';
$phone = isset($data['phone']) ? $data['phone'] : '';
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
		// query messages
		$messages = $message->get_all($message_id);
		$total = $messages->rowCount();
		  
		if($total) {
		  while ($row = $messages->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "message_id" => $message_id,
	        "customer_id" => $customer_id,
	        "customer_first_name" => $customer_first_name,
	        "customer_last_name" => $customer_last_name,
	        "employee_id" => $employee_id,
	        "employee_first_name" => $employee_first_name,
	        "employee_last_name" => $employee_last_name,
	        "date" => $date,
	        "to_phone" => $to_phone,
	        "from_phone" => $from_phone,
	        "direction" => $direction,
	        "message" => $message,
	        "read" => $read,
	        "uuid" => $uuid,
	        "status" => $status,
		    );

		    $output["data"][] = $result;
		  }

		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  echo json_encode(
		      array("status"=>"error", "message" => "No messages found.")
		  );
		}
		break;
	case "find_by_direction":
		if (!$direction) {
			echo json_encode(
		    array("status"=>"error", "message" => "You must specify a direction(incoming/outgoing) to query messages.")
		  );
		  return;
		}

		$messages = $message->get_message_by_direction($direction);
		$total = $messages->rowCount();

		if($total) {
		  while ($row = $messages->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "message_id" => $message_id,
	        "customer_id" => $customer_id,
	        "customer_first_name" => $customer_first_name,
	        "customer_last_name" => $customer_last_name,
	        "employee_id" => $employee_id,
	        "employee_first_name" => $employee_first_name,
	        "employee_last_name" => $employee_last_name,
	        "date" => $date,
	        "to_phone" => $to_phone,
	        "from_phone" => $from_phone,
	        "direction" => $direction,
	        "message" => $message,
	        "read" => $read,
	        "uuid" => $uuid,
	        "status" => $status
		    );

		    $output["data"][] = $result;
		  }

		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  echo json_encode(
		      array("status"=>"error", "message" => "No messages found.")
		  );
		}
		break;
	case "unread_total":
		$msg = $message->get_unread_count();
		$row = $msg->fetch(PDO::FETCH_ASSOC);

		if (isset($row['unread_count'])){
			$output["status"] =  "success";
			$output["data"] = $row['unread_count'];
	  	echo json_encode($output);
		} else {
		  echo json_encode(
		      array("status"=>"error", "message" => "No messages found.")
		  );
		}
		break;
	case "get_conversation":
		if (!$phone) {
			echo json_encode(
		    array("status"=>"error", "message" => "You must specify a phone number in order to retrieve a conversation")
		  );
		  return;
		}

		$messages = $message->get_conversation($phone);
		$total = $messages->rowCount();

		if($total) {
		  while ($row = $messages->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "message_id" => $message_id,
	        "customer_id" => $customer_id,
	        "customer_first_name" => $customer_first_name,
	        "customer_last_name" => $customer_last_name,
	        "employee_id" => $employee_id,
	        "employee_first_name" => $employee_first_name,
	        "employee_last_name" => $employee_last_name,
	        "date" => $date,
	        "to_phone" => $to_phone,
	        "from_phone" => $from_phone,
	        "direction" => $direction,
	        "message" => $message,
	        "read" => $read,
	        "uuid" => $uuid,
	        "status" => $status
		    );

		    $output["data"][] = $result;
		  }

		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  echo json_encode(
		      array("status"=>"error", "message" => "No messages found.")
		  );
		}
		break;
	case "update":
		$message->read = isset($data['read']) ? $data['read'] : '';

	  if ($message->update($message_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Message updated";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error updating the message";
	  }
		
	  echo json_encode($output);
		break;			
}

