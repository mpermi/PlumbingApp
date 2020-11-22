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

  $database = new Database();
  $db = $database->connect();

  $uuid = isset($_POST['MessageUUID']) ? $_POST['MessageUUID'] : '';

  // query messages
  $message = new Message($db);
  $query = $message->get_all($uuid);
  $total = $query->rowCount();

  //TODO search for customer record when that class is done
  //$message->customer_id = ??;
  $message->date = date('Y-m-d H:i:s');
  $message->to_phone = '14102614888';
  $message->from_phone = isset($_POST['From']) ? $_POST['From'] : '';
  $message->direction = 'incoming';
  $message->message = isset($_POST['Text']) ? $_POST['Text'] : '';
  $message->uuid = isset($_POST['MessageUUID']) ? $_POST['MessageUUID'] : '';
  $message->created = date('Y-m-d H:i:s');

  if ($total && $uuid) {
    $message->update();
  } else {
    $message->create();
  }
