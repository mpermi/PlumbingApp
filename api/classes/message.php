
<?php
class Message {
  private $connection;
  private $table_name = "messages";

  public $message_id;
  public $customer_id;
  public $employee_id;
  public $date;
  public $to_phone;
  public $from_phone;
  public $direction;
  public $message;
  public $uuid;
  public $created;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all messages
	public function get_all($uuid = '') {
    $sql = "";
    if ($uuid) {
      $sql = " WHERE messages.uuid = :uuid";
    }
    $query = "SELECT
                  message_id,
                  customer_id,
                  employee_id,
                  date,
                  to_phone,
                  from_phone,
                  direction,
                  message,
                  uuid,
                  created
		          FROM
		              " . $this->table_name . 
                  $sql .
		          " ORDER BY
		              created, direction DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':message_id', $uuid);
    $result->execute();
  
    return $result;
	}

  //update existing message
  public function update() {
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  date = :date,
                  to_phone = :to_phone,
                  from_phone = :from_phone,
                  direction = :direction,
                  message = :message,
                  uuid = :uuid
              WHERE
                  message_id = :message_id";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':message_id', filter_var($this->message_id, FILTER_SANITIZE_STRING));
    $result->bindParam(':date', filter_var($this->date, FILTER_SANITIZE_STRING));
    $result->bindParam(':to_phone', filter_var($this->to_phone, FILTER_SANITIZE_STRING));
    $result->bindParam(':from_phone', filter_var($this->from_phone, FILTER_SANITIZE_STRING));
    $result->bindParam(':direction', filter_var($this->direction, FILTER_SANITIZE_STRING));
    $result->bindParam(':message', filter_var($this->message, FILTER_SANITIZE_STRING));
    $result->bindParam(':uuid', filter_var($this->uuid, FILTER_SANITIZE_STRING));
    $result->execute();

    return $result;
  }

  //create new message
  public function create() {
    $query = "INSERT INTO
                " . $this->table_name . "
                (date, to_phone, from_phone, direction, message, uuid, created)
              VALUES (:date, :to_phone, :from_phone, :direction, :message, :uuid, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':date', filter_var($this->date, FILTER_SANITIZE_STRING));
    $result->bindParam(':to_phone', filter_var($this->to_phone, FILTER_SANITIZE_STRING));
    $result->bindParam(':from_phone', filter_var($this->from_phone, FILTER_SANITIZE_STRING));
    $result->bindParam(':direction', filter_var($this->direction, FILTER_SANITIZE_STRING));
    $result->bindParam(':message', filter_var($this->message, FILTER_SANITIZE_STRING));
    $result->bindParam(':uuid', filter_var($this->uuid, FILTER_SANITIZE_STRING));
    $result->bindParam(':created', filter_var($this->created, FILTER_SANITIZE_STRING));
    $result->execute();

    return $result;
  }  
}
?>