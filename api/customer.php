<?php
// required headers
header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");  

//database config file and class
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'classes/customer.php';
include_once 'classes/address.php';

// instantiate database and customer object
$database = new Database();
$db = $database->connect();
  
$customer = new Customer($db);
$address = new Address($db);

if ($_SERVER['REQUEST_METHOD'] =='GET') {
	$data = $_GET;
} else if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$data = $_POST;
} else {
	$data=array();
}

$customer_id = isset($data['customer_id']) ? $data['customer_id'] : '';
$phone = isset($data['phone']) ? $data['phone'] : '';
$request = isset($data['request']) ? $data['request'] : '';
$output=array();

// $decoded_data = json_decode(file_get_contents('php://input')); 

switch ($request) {
	case "find":
		// query customers
		$customers = $customer->get_all($customer_id);
		$total = $customers->rowCount();
		  
		if($total) {
		  while ($row = $customers->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "customer_id" => $customer_id,
	        "first_name" => $first_name,
	        "last_name" => $last_name,
	        "address_id" => $address_id,
	        "address1" => $address1,
	        "address2" => $address2,
	        "city" => $city,
	        "state" => $state,
	        "zipcode" => $zipcode,
	        "phone" => $phone
		    );

		    $output["data"][] = $result;
		  }

		  //http_response_code(200);
		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  //http_response_code(404);
		  echo json_encode(
		      array("status"=>"error", "message" => "No customers found.")
		  );
		}
		break;
	case "find_by_phone":
		// query customers
		$customers = $customer->get_customer_by_phone($phone);
		$total = $customers->rowCount();

		if($total) {
		  while ($row = $customers->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "customer_id" => $customer_id,
	        "first_name" => $first_name,
	        "last_name" => $last_name,
	        "address_id" => $address_id,
	        "address1" => $address1,
	        "address2" => $address2,
	        "city" => $city,
	        "state" => $state,
	        "zipcode" => $zipcode,
	        "phone" => $phone
		    );

		    $output["data"][] = $result;
		  }

		  //http_response_code(200);
		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  //http_response_code(404);
		  echo json_encode(
		      array("status"=>"error", "message" => "No customers found.")
		  );
		}
		break;
	case "add":
		$address_id = '';
		$address->address1 = isset($data['address1']) ? $data['address1'] : '';
	  $address->address2 = isset($data['address2']) ? $data['address2'] : '';
	  $address->city = isset($data['city']) ? $data['city'] : '';
	  $address->state = isset($data['state']) ? $data['state'] : '';
	  $address->zipcode = isset($data['zipcode']) ? $data['zipcode'] : '';
	  $address->created = date('Y-m-d H:i:s');
	  $address->create();
	  $address_id = $db->lastInsertId();

	  $customer->first_name = isset($data['first_name']) ? $data['first_name'] : '';
	  $customer->last_name = isset($data['last_name']) ? $data['last_name'] : '';
	  $customer->phone = isset($data['phone']) ? $data['phone'] : '';
	  $customer->address_id = $address_id;
	  $customer->created = date('Y-m-d H:i:s');

	  if ($customer->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Customer added";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error adding customer";
	  }

	  echo json_encode($output);
		break;
	case "update":
		$address_id = '';

		if (!isset($data['address_id'])) {
			$address->address1 = isset($data['address1']) ? $data['address1'] : '';
		  $address->address2 = isset($data['address2']) ? $data['address2'] : '';
		  $address->city = isset($data['city']) ? $data['city'] : '';
		  $address->state = isset($data['state']) ? $data['state'] : '';
		  $address->zipcode = isset($data['zipcode']) ? $data['zipcode'] : '';
		  $address->created = date('Y-m-d H:i:s');
		  $address->create();
		  $address_id = $db->lastInsertId();
		} else {
			$address->address1 = isset($data['address1']) ? $data['address1'] : '';
		  $address->address2 = isset($data['address2']) ? $data['address2'] : '';
		  $address->city = isset($data['city']) ? $data['city'] : '';
		  $address->state = isset($data['state']) ? $data['state'] : '';
		  $address->zipcode = isset($data['zipcode']) ? $data['zipcode'] : '';
		  $address->update($data['address_id']);
		}

	  $customer->first_name = isset($data['first_name']) ? $data['first_name'] : '';
	  $customer->last_name = isset($data['last_name']) ? $data['last_name'] : '';
	  $customer->phone = isset($data['phone']) ? $data['phone'] : '';
	  $customer->address_id = isset($data['address_id']) ? $data['address_id'] : $address_id;

	  if ($customer->update($customer_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Customer updated";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error updating customer";
	  }
		
	  echo json_encode($output);
		break;
	case "delete":
		if (!$customer_id) {
			echo json_encode(
		    array("status"=>"error", "message" => "You must provide a customer id to delete.")
		  );
		}
	  if ($customer->delete($customer_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Customer deleted";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error deleting customer";
	  }
		
	  echo json_encode($output);
		break;		
}

