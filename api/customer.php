<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//database config file and class
include_once 'config/database.php';
include_once 'classes/customer.php';
  
// instantiate database and customer object
$database = new Database();
$db = $database->connect();
  
$customer = new Customer($db);
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
$phone = isset($_GET['phone']) ? $_GET['phone'] : '';
$request = isset($_GET['request']) ? $_GET['request'] : '';
$output=array();

$decoded_data = json_decode(file_get_contents('php://input')); 

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
		file_put_contents('/tmp/fgdfgdfgdfg.php', $phone);
		$total = $customers->rowCount();
		  file_put_contents('/tmp/fgdfgdfgdfg1111111.php', $total);
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
	  $customer->first_name = $decoded_data->first_name ? $decoded_data->first_name : '';
	  $customer->last_name = $decoded_data->last_name ? $decoded_data->last_name : '';
	  $customer->phone = $decoded_data->phone ? $decoded_data->phone : '';
	  $customer->address_id = $decoded_data->address_id ? $decoded_data->address_id : '';
	  $customer->created = date('Y-m-d H:i:s');

	  if ($customer->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Customer added";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error adding customer";
	  }

	  echo json_encode($output);
		break;
	case "update":
	  $customer->first_name = $decoded_data->first_name ? $decoded_data->first_name : '';
	  $customer->last_name = $decoded_data->last_name ? $decoded_data->last_name : '';
	  $customer->phone = $decoded_data->phone ? $decoded_data->phone : '';
	  $customer->address_id = $decoded_data->address_id ? $decoded_data->address_id : '';

	  if ($customer->update($customer_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Customer updated";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error updating customer";
	  }
		
	  echo json_encode($output);
		break;
	case "delete":
	  if ($customer->delete($customer_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Customer deleted";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error deleting customer";
	  }
		
	  echo json_encode($output);
		break;		
}

