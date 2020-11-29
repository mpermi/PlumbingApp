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

if ($_SERVER['REQUEST_METHOD'] =='GET') {
	$data = $_GET;
} else if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$data = $_POST;
} else {
	$data=array();
}
$employee_id = isset($data['employee_id']) ? $data['employee_id'] : '';
$username = isset($data['username']) ? $data['username'] : '';
$request = isset($data['request']) ? $data['request'] : '';
$output=array();

// $decoded_data = json_decode(file_get_contents('php://input')); 

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
	  $employee->first_name = isset($data['first_name']) ? $data['first_name'] : '';
	  $employee->last_name = isset($data['last_name']) ? $data['last_name'] : '';
	  $employee->username = isset($data['username']) ? $data['username'] : '';
	  $employee->password = isset($data['password']) ? $data['password'] : '';
	  $employee->title_id = isset($data['title_id']) ? $data['title_id'] : '';
	  $employee->phone = isset($data['phone']) ? $data['phone'] : '';
	  $employee->created = date('Y-m-d H:i:s');

	  if ($employee->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Employee added";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error adding employee";
	  }

	  echo json_encode($output);
		break;
	case "update":
		$current_employee = $employee->get_all($employee_id);
		$total = $current_employee->rowCount();
		$current_password = '';

		if ($total) {
			extract($current_employee->fetch(PDO::FETCH_ASSOC));
			$current_password = $password;
		} 

	  $employee->first_name = isset($data['first_name']) ? $data['first_name'] : '';
	  $employee->last_name = isset($data['last_name']) ? $data['last_name'] : '';
	  $employee->password = isset($data['password']) && $current_password == $data['password'] ? $data['password'] : password_hash($data['password'], PASSWORD_BCRYPT);
	  $employee->title_id = isset($data['title_id']) ? $data['title_id'] : '';
	  $employee->phone = isset($data['phone']) ? $data['phone'] : '';

	  if ($employee->update($employee_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Employee updated";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error updating employee";
	  }
		
	  echo json_encode($output);
		break;
	case "delete":
		if (!$employee_id) {
			echo json_encode(
		    array("status"=>"error", "message" => "You must provide an employee id to delete.")
		  );
		}
	  if ($employee->delete($employee_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Employee deleted";
	  } else {
	  	$output["status"] =  "error";
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
		  }
		}    
	  echo json_encode($output);
		break;		
}

