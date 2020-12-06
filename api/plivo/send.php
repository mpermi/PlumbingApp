<?php
  //request headers
  header("Access-Control-Allow-Origin: *");
  // header("Content-Type: application/json;");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //database config file and class
  include_once '../config/database.php';
  include_once '../config/core.php';
  include_once '../classes/message.php';
  include_once '../classes/plivo_api.php';

  $database = new Database();
  $db = $database->connect();

  $from_phone = $plivo_phone;
  $to_phone = isset($_POST['phone']) ? $_POST['phone'] : '';
  $message_text = isset($_POST['message']) ? $_POST['message'] : '';
  $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : 0;
  $employee_id = isset($_POST['employee_id']) ? $_POST['employee_id'] : 0;

  if (!$to_phone || !$message_text) {
    echo json_encode(array('status'=>'error', 'data'=>'Error, please try again. No phone number or text provided.'));
  }

  // //prepare the parameters for the Plivo api call
  $params = array();

  $params['src'] = $from_phone;
  $params['dst'] = $to_phone;
  $params['text'] = $message_text;
  $params['url'] = 'https://354c1d3d5fd8.ngrok.io/api/plivo/callback.php';

  $plivo_api = new Plivo_API();
  $response = $plivo_api->send_sms($params);
  $decoded_response = json_decode($response, true);

  //add the new message that was sent
  $message = new Message($db);

  $message->customer_id = $customer_id;
  $message->employee_id = $employee_id;
  $message->date = date('Y-m-d H:i:s');
  $message->to_phone = $to_phone;
  $message->from_phone = $from_phone;
  $message->direction = 'outgoing';
  $message->message = $message_text;
  $message->uuid = isset($decoded_response['message_uuid']) ? $decoded_response['message_uuid'][0] : '';
  $message->read = 1;
  $message->status = 'delivered';
  $message->created = date('Y-m-d H:i:s');
  

  if ($message->create()) {
    echo json_encode(array('status'=>'success', 'data'=>'Message sent.'));
  } else {
    echo json_encode(array('status'=>'error', 'data'=>'There was an error sending this message.'));
  }
  


