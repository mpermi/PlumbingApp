<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//database config file and class
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'classes/employee.php';
require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;

// instantiate database and employee object
$database = new Database();
$db = $database->connect();
  
$employee = new Employee($db);
$employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : '';
$username = isset($_GET['username']) ? $_GET['username'] : '';
$request = isset($_GET['request']) ? $_GET['request'] : '';
$output=array();

$decoded_data = json_decode(file_get_contents('php://input')); 

switch ($request) {
	case "find":
		// query employees
		$employees = $employee->get_all($employee_id);
		$total = $employees->rowCount();
		  
		if ($total) {
		  while ($row = $employees->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
		        "employee_id" => $employee_id,
		        "first_name" => $first_name,
		        "last_name" => $last_name,
		        "username" => $username,
		        "password" => $password,
		        "title_id" => $title_id,
		        "title" => $title,
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
		      array("status"=>"error", "message" => "No employees found.")
		  );
		}
		break;
	case "add":
	  $employee->first_name = $decoded_data->first_name ? $decoded_data->first_name : '';
	  $employee->last_name = $decoded_data->last_name ? $decoded_data->last_name : '';
	  $employee->username = $decoded_data->username ? $decoded_data->username : '';
	  $employee->password = $decoded_data->password ? $decoded_data->password : '';
	  $employee->title_id = $decoded_data->title_id ? $decoded_data->title_id : '';
	  $employee->phone = $decoded_data->phone ? $decoded_data->phone : '';
	  $employee->created = date('Y-m-d H:i:s');

	  if ($employee->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Employee added";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error adding employee";
	  }

	  echo json_encode($output);
		break;
	case "update":
	  $employee->first_name = $decoded_data->first_name ? $decoded_data->first_name : '';
	  $employee->last_name = $decoded_data->last_name ? $decoded_data->last_name : '';
	  $employee->username = $decoded_data->username ? $decoded_data->username : '';
	  $employee->password = $decoded_data->password ? $decoded_data->password : '';
	  $employee->title_id = $decoded_data->title_id ? $decoded_data->title_id : '';
	  $employee->phone = $decoded_data->phone ? $decoded_data->phone : '';

	  if ($employee->update($employee_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Employee updated";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error updating employee";
	  }
		
	  echo json_encode($output);
		break;
	case "delete":
	  if ($employee->delete($employee_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Employee deleted";
	  } else {
	  	$output["error"] =  "error";
			$output["data"] =  "There was an error deleting employee";
	  }
		
	  echo json_encode($output);
		break;
	case "login":
		$employees = $employee->get_employee_by_username($username);
		$total = $employees->rowCount();
		
		if ($total) {
		  while ($row = $employees->fetch(PDO::FETCH_ASSOC)){
		    extract($row);
		    file_put_contents('/tmp/fgdfgdfgdgf.php', $first_name);
		  }
		}    
	  echo json_encode($output);
		break;		
}

