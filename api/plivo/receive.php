<?php
  //request headers
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json;");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  //database config file and class
  include_once '../config/database.php';
  include_once '../config/core.php';
  include_once '../classes/message.php';
  include_once '../classes/customer.php';

  $database = new Database();
  $db = $database->connect();

  $uuid = isset($_POST['MessageUUID']) ? $_POST['MessageUUID'] : '';
  $from_phone = isset($_POST['From']) ? $_POST['From'] : '';

  // query messages
  $message = new Message($db);
  $query = $message->get_message_by_uuid($uuid);
  $total = $query->rowCount();

  //try to find a customer record that matches the phone #
  $customer_id = 0;
  $customer = new Customer($db);
  $customers = $customer->get_customer_by_phone($from_phone);
  $total_customers = $customers->rowCount();
  
  if ($total_customers && $total_customers == 1) {
    $row = $customers->fetch(PDO::FETCH_ASSOC);
    extract($row);
  }

  $message->customer_id = $customer_id;
  $message->employee_id = 0;
  $message->date = date('Y-m-d H:i:s');
  $message->to_phone = isset($_POST['To']) ? $_POST['To'] : $plivo_phone;
  $message->from_phone = $from_phone;
  $message->direction = 'incoming';
  $message->message = isset($_POST['Text']) ? $_POST['Text'] : '';
  $message->uuid = isset($_POST['MessageUUID']) ? $_POST['MessageUUID'] : '';
  $message->read = 0;
  $message->created = date('Y-m-d H:i:s');

  if ($total && $uuid) {
    $message->update('', $uuid);
  } else {
    $message->create();
  }
