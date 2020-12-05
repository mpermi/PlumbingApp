<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");  

//database config file and class
include_once 'config/core.php';
include_once 'config/database.php';
include_once 'classes/job.php';
require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';

use \Firebase\JWT\JWT;
  
// instantiate database and job object
$database = new Database();
$db = $database->connect();
$job = new Job($db);

if ($_SERVER['REQUEST_METHOD'] =='GET') {
	$data = $_GET;
} else if ($_SERVER['REQUEST_METHOD'] =='POST') {
	$data = $_POST;
} else {
	$data=array();
}

$job_id = isset($data['job_id']) ? $data['job_id'] : '';
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
		// query jobs
		$jobs = $job->get_all($job_id);
		$total = $jobs->rowCount();
		  
		if($total) {
		  while ($row = $jobs->fetch(PDO::FETCH_ASSOC)){
		    extract($row);

		    $result=array(
	        "job_id" => $job_id,
	        "date" => $date,
	        "customer_id" => $customer_id,
	        "customer_first_name" => $customer_first_name,
	        "customer_last_name" => $customer_last_name,
	        "customer_phone" => $customer_phone,
					"customer_address1" => $customer_address1,
					"customer_address2" => $customer_address2,
					"customer_city" => $customer_city,
					"customer_state" => $customer_state,
					"customer_zipcode" => $customer_zipcode,	        
	        "issue" => $issue,
	        "employee_id" => $employee_id,
	        "employee_first_name" => $employee_first_name,
	        "employee_last_name" => $employee_last_name,	        
		    );

		    $output["data"][] = $result;
		  }

		  $output["status"] =  "success";
		  echo json_encode($output);
		} else {
		  echo json_encode(
		      array("status"=>"error", "message" => "No jobs found.")
		  );
		}
		break;
	case "add":
	  $job->date = isset($data['date']) ? $data['date'] : '';
	  $job->customer_id = isset($data['customer_id']) ? $data['customer_id'] : '';
	  $job->issue = isset($data['issue']) ? $data['issue'] : '';
	  $job->employee_id = isset($data['employee_id']) ? $data['employee_id'] : '';
	  $job->created = date('Y-m-d H:i:s');

	  if ($job->create()) {
			$output["status"] =  "success";
		  $output["data"] =  "Job added";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error adding job";
	  }

	  echo json_encode($output);
		break;
	case "delete":
		if (!$job_id) {
			echo json_encode(
		    array("status"=>"error", "message" => "You must provide a job id to delete.")
		  );
		  return;
		}

	  if ($job->delete($job_id)) {
	  	$output["status"] =  "success";
			$output["data"] =  "Job deleted";
	  } else {
	  	$output["status"] =  "error";
			$output["data"] =  "There was an error deleting job";
	  }
		
	  echo json_encode($output);
		break;		
}

