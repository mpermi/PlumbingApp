
<?php
class Address {
  private $connection;
  private $table_name = "addresses";

  public $address_id;
  public $address1;
  public $address2;
  public $city;
  public $state;
  public $zipcode;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all addresses
	public function get_all($address_id = '') {
    $sql = "";
    if ($address_id) {
      $sql = " WHERE addresses.address_id = :address_id";
    }
    $query = "SELECT
                address_id,
                address1,
                address2,
                city,
                state,
                zipcode
		          FROM " . $this->table_name .
                  $sql;
    $result = $this->connection->prepare($query);
    $result->bindParam(':address_id', $address_id);
    $result->execute();
  
    return $result;
	}

  //create new address
  public function create() {
    $address1 = filter_var($this->address1, FILTER_SANITIZE_STRING);
    $address2 = filter_var($this->address2, FILTER_SANITIZE_STRING);
    $city = filter_var($this->city, FILTER_SANITIZE_STRING);
    $state = filter_var($this->state, FILTER_SANITIZE_STRING);
    $zipcode = filter_var($this->zipcode, FILTER_SANITIZE_STRING);
    $created = filter_var($this->created, FILTER_SANITIZE_STRING);

    $query = "INSERT INTO
                " . $this->table_name . "
                (address1, address2, city, state, zipcode, created)
              VALUES (:address1, :address2, :city, :state, :zipcode, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':address1', $address1);
    $result->bindParam(':address2', $address2);
    $result->bindParam(':city', $city);
    $result->bindParam(':state', $state);
    $result->bindParam(':zipcode', $zipcode);    
    $result->bindParam(':created', $created);
    $result->execute();

    return $result;
  }

  //update address
  public function update($address_id) {
    $address1 = filter_var($this->address1, FILTER_SANITIZE_STRING);
    $address2 = filter_var($this->address2, FILTER_SANITIZE_STRING);
    $city = filter_var($this->city, FILTER_SANITIZE_STRING);
    $state = filter_var($this->state, FILTER_SANITIZE_STRING);
    $zipcode = filter_var($this->zipcode, FILTER_SANITIZE_STRING);

    $query = "UPDATE
                " . $this->table_name . "
              SET
                  address1 = :address1,
                  address2 = :address2,
                  city = :city,
                  state = :state,
                  zipcode = :zipcode
              WHERE
                  address_id = :address_id";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':address1', $address1);
    $result->bindParam(':address2', $address2);
    $result->bindParam(':city', $city);
    $result->bindParam(':state', $state);
    $result->bindParam(':zipcode', $zipcode);
    $result->bindParam(':address_id', $address_id);
    $result->execute();

    return $result;
  }

  //delete address
  public function delete($address_id) {
    $query = "DELETE FROM
                " . $this->table_name . "
                WHERE
                  address_id = :address_id";

    $result = $this->connection->prepare($query);
    $result->bindParam(':address_id', $address_id);
    $result->execute();

    return $result;
  } 
}
?>