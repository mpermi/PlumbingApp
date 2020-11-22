<?php
  //request headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json;");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //database config file and class
  include_once '../config/database.php';
  include_once '../classes/message.php';
  include_once '../classes/plivo_api.php';

  $database = new Database();
  $db = $database->connect();

  $decoded_data = json_decode(file_get_contents('php://input')); 

  $from_phone = '14102614888';
  $to_phone = $decoded_data->phone ? $decoded_data->phone : '';
  $message_text = $decoded_data->message ? $decoded_data->message : '';

  //prepare the parameters for the Plivo api call
  $params = array();

  $params['src'] = $from_phone;
  $params['dst'] = $to_phone;
  $params['text'] = $message_text;
  $parmas['url'] = 'http://917faa0e9c2d.ngrok.io/mobileapi/plivo/callback.php';

  $plivo_api = new Plivo_API();
  $response = $plivo_api->send_sms($params);
  $decoded_response = json_decode($response, true);

  //add the new message that was sent
  $message = new Message($db);

  //TODO search for customer record when that class is done
  //$message->customer_id = ??;
  $message->date = date('Y-m-d H:i:s');
  $message->to_phone = $to_phone;
  $message->from_phone = $from_phone;
  $message->direction = 'outgoing';
  $message->message = $message_text;
  $message->uuid = isset($decoded_response['message_uuid']) ? $decoded_response['message_uuid'][0] : '';
  $message->created = date('Y-m-d H:i:s');
  $message->create();

  echo json_encode(array('status'=>'success', 'data'=>'Message sent.'));


