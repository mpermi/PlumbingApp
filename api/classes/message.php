
<?php
class Message {
  private $connection;
  private $table_name = "messages";

  public $message_id;
  public $customer_id;
  public $customer_first_name;
  public $customer_last_name;
  public $employee_id;
  public $employee_first_name;
  public $employee_last_name;
  public $date;
  public $to_phone;
  public $from_phone;
  public $direction;
  public $message;
  public $read;
  public $uuid;
  public $status;
  public $created;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all messages
	public function get_all($message_id = '') {
    $sql = "";
    if ($message_id) {
      $sql = " WHERE messages.message_id = :message_id";
    }
    $query = "SELECT
                  message_id,
                  customers.customer_id,
                  customers.first_name as customer_first_name,
                  customers.last_name as customer_last_name,
                  employees.employee_id,
                  employees.first_name as employee_first_name,
                  employees.last_name as employee_last_name,
                  date,
                  to_phone,
                  from_phone,
                  direction,
                  message,
                  `read`,
                  uuid,
                  status,
                  messages.created
		          FROM
		              " . $this->table_name . 
              " LEFT JOIN customers ON customers.customer_id = messages.customer_id
               LEFT JOIN employees ON employees.employee_id = messages.employee_id"
                .  $sql .
		          " ORDER BY
		              messages.created, direction DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':message_id', $message_id);
    $result->execute();
  
    return $result;
	}

  //get all messages by direction(incoming/outgoing)
  public function get_message_by_direction($direction) {
    $sql = "";
    if ($direction) {
      $sql = " WHERE messages.direction = :direction";
    }
    $query = "SELECT
                  message_id,
                  customers.customer_id,
                  customers.first_name as customer_first_name,
                  customers.last_name as customer_last_name,
                  employees.employee_id,
                  employees.first_name as employee_first_name,
                  employees.last_name as employee_last_name,
                  date,
                  to_phone,
                  from_phone,
                  direction,
                  message,
                  `read`,
                  uuid,
                  status,
                  messages.created
              FROM
                  " . $this->table_name . 
              " LEFT JOIN customers ON customers.customer_id = messages.customer_id
               LEFT JOIN employees ON employees.employee_id = messages.employee_id"
                .  $sql .
              " ORDER BY
                  messages.date DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':direction', $direction);
    $result->execute();
  
    return $result;
  }

  //get all messages by uuid
  public function get_message_by_uuid($uuid) {
    $sql = "";
    if ($uuid) {
      $sql = " WHERE messages.uuid = :uuid";
    }
    $query = "SELECT
                  message_id,
                  customers.customer_id,
                  customers.first_name as customer_first_name,
                  customers.last_name as customer_last_name,
                  employees.employee_id,
                  employees.first_name as employee_first_name,
                  employees.last_name as employee_last_name,
                  date,
                  to_phone,
                  from_phone,
                  direction,
                  message,
                  `read`,
                  uuid,
                  status,
                  messages.created
              FROM
                  " . $this->table_name . 
              " LEFT JOIN customers ON customers.customer_id = messages.customer_id
               LEFT JOIN employees ON employees.employee_id = messages.employee_id"
                .  $sql .
              " ORDER BY
                  messages.created, direction DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':uuid', $uuid);
    $result->execute();
  
    return $result;
  }

  //get total number of unread messages
  public function get_unread_count() {
    $query = "SELECT
                  COUNT(*) as unread_count
              FROM
                  " . $this->table_name .
              " WHERE direction = 'incoming'
              AND `read` = 0";
    $result = $this->connection->prepare($query);
    $result->execute();
  
    return $result;
  }

  //update existing message
  public function update($message_id='', $uuid='') {
    if ($message_id) {
      $sql = " WHERE messages.message_id = :message_id";
    }
    if ($uuid) {
      $sql = " WHERE messages.uuid = :uuid";
    }
    $read = filter_var($this->read, FILTER_SANITIZE_STRING);
    $status = filter_var($this->status, FILTER_SANITIZE_STRING);
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  `read` = :read,
                  status = :status
              " . $sql;
  
    $result = $this->connection->prepare($query);
    if ($message_id) {
      $result->bindParam(':message_id', $message_id);
    }
    if ($uuid) {
      $result->bindParam(':uuid', $uuid);
    }
    $result->bindParam(':read', $read);
    $result->bindParam(':status', $status);
    $result->execute();

    return $result;
  }

  //create new message
  public function create() {
    $date = filter_var($this->date, FILTER_SANITIZE_STRING);
    $to_phone = filter_var($this->to_phone, FILTER_SANITIZE_STRING);
    $from_phone = filter_var($this->from_phone, FILTER_SANITIZE_STRING);
    $direction = filter_var($this->direction, FILTER_SANITIZE_STRING);
    $message = filter_var($this->message, FILTER_SANITIZE_STRING);
    $employee_id = filter_var($this->employee_id, FILTER_SANITIZE_STRING);
    $customer_id = filter_var($this->customer_id, FILTER_SANITIZE_STRING);
    $uuid = filter_var($this->uuid, FILTER_SANITIZE_STRING);
    $read = filter_var($this->read, FILTER_SANITIZE_STRING);
    $status = filter_var($this->status, FILTER_SANITIZE_STRING);
    $created = filter_var($this->created, FILTER_SANITIZE_STRING);

    $query = "INSERT INTO
                " . $this->table_name . "
                (date, to_phone, from_phone, direction, message, employee_id, customer_id, uuid, `read`, status, created)
              VALUES (:date, :to_phone, :from_phone, :direction, :message, :employee_id, :customer_id, :uuid, :read, :status, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':date', $date);
    $result->bindParam(':to_phone', $to_phone);
    $result->bindParam(':from_phone', $from_phone);
    $result->bindParam(':direction', $direction);
    $result->bindParam(':message', $message);
    $result->bindParam(':employee_id', $employee_id);
    $result->bindParam(':customer_id', $customer_id);
    $result->bindParam(':uuid', $uuid);
    $result->bindParam(':read', $read);
    $result->bindParam(':status', $status);
    $result->bindParam(':created', $created);
    $result->execute();

    return $result;
  }

  //get the conversation
  public function get_conversation($phone) {
    $sql = "";
    if ($phone) {
      $sql = " WHERE messages.from_phone = :phone OR messages.to_phone = :phone";
    }
    $query = "SELECT
                  message_id,
                  customers.customer_id,
                  customers.first_name as customer_first_name,
                  customers.last_name as customer_last_name,
                  employees.employee_id,
                  employees.first_name as employee_first_name,
                  employees.last_name as employee_last_name,
                  date,
                  to_phone,
                  from_phone,
                  direction,
                  message,
                  `read`,
                  uuid,
                  status,
                  messages.created
              FROM
                  " . $this->table_name . 
              " LEFT JOIN customers ON customers.customer_id = messages.customer_id
               LEFT JOIN employees ON employees.employee_id = messages.employee_id"
                .  $sql .
              " ORDER BY
                  messages.created, direction DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':phone', $phone);
    $result->execute();
  
    return $result;
  }   
}
?>