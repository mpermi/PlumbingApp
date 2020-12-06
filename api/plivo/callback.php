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
  $plivo_status = isset($_POST['Status']) ? $_POST['Status'] : '';

  // query messages
  $message = new Message($db);
  $messages = $message->get_message_by_uuid($uuid);
  $total = $messages->rowCount();
  $row = $messages->fetch(PDO::FETCH_ASSOC);
  extract($row);

  $current_message = new Message($db);
  $current_message->status = $plivo_status;
  $current_message->read = $read;

  if ($total && $uuid) {
    $current_message->update('', $uuid);
  }
