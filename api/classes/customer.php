
<?php
class Customer {
  private $connection;
  private $table_name = "customers";

  public $customer_id;
  public $first_name;
  public $last_name;
  public $phone;
  public $address_id;
  public $address1;
  public $address2;
  public $city;
  public $state;
  public $zipcode;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all customers
	public function get_all($customer_id = '') {
    $sql = "";
    if ($customer_id) {
      $sql = " WHERE customers.customer_id = :customer_id";
    }
    $query = "SELECT
                customer_id,
                first_name, 
                last_name, 
                addresses.address_id,
                addresses.address1,
                addresses.address2,
                addresses.city,
                addresses.state,
                addresses.zipcode,
                phone
		          FROM " . $this->table_name . 
              " LEFT JOIN addresses ON addresses.address_id = customers.address_id" .
                  $sql .
		          " ORDER BY
		              first_name, last_name DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':customer_id', $customer_id);
    $result->execute();
  
    return $result;
	}

  //find customer by the phone #
  public function get_customer_by_phone($phone) {
    $query = "SELECT
                customer_id,
                first_name, 
                last_name, 
                addresses.address_id,
                addresses.address1,
                addresses.address2,
                addresses.city,
                addresses.state,
                addresses.zipcode,
                phone
              FROM " . $this->table_name .
              " LEFT JOIN addresses ON addresses.address_id = customers.address_id
               WHERE customers.phone = :phone 
              ORDER BY
                first_name, last_name DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':phone', $phone);
    $result->execute();
  
    return $result;
  }

  //update existing customer
  public function update($customer_id) {
    $query = "UPDATE
                " . $this->table_name . "
              SET
                  first_name = :first_name,
                  last_name = :last_name,
                  phone = :phone,
                  address_id = :address_id
              WHERE
                  customer_id = :customer_id";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':customer_id', $customer_id);
    $result->bindParam(':first_name', filter_var($this->first_name, FILTER_SANITIZE_STRING));
    $result->bindParam(':last_name', filter_var($this->last_name, FILTER_SANITIZE_STRING));
    $result->bindParam(':phone', filter_var($this->phone, FILTER_SANITIZE_STRING));
    $result->bindParam(':address_id', filter_var($this->address_id, FILTER_SANITIZE_STRING));
    $result->execute();

    return $result;
  }

  //create new customer
  public function create() {
    $query = "INSERT INTO
                " . $this->table_name . "
                (first_name, last_name, phone, address_id, created)
              VALUES (:first_name, :last_name, :phone, :address_id, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':first_name', filter_var($this->first_name, FILTER_SANITIZE_STRING));
    $result->bindParam(':last_name', filter_var($this->last_name, FILTER_SANITIZE_STRING));
    $result->bindParam(':phone', filter_var($this->phone, FILTER_SANITIZE_STRING));
    $result->bindParam(':address_id', filter_var($this->address_id, FILTER_SANITIZE_STRING));
    $result->bindParam(':created', filter_var($this->created, FILTER_SANITIZE_STRING));
    $result->execute();

    return $result;
  }

    //delete employee
  public function delete($customer_id) {
    $query = "DELETE FROM
                " . $this->table_name . "
                WHERE
                  customer_id = :customer_id";

    $result = $this->connection->prepare($query);
    $result->bindParam(':customer_id', $customer_id);
    $result->execute();

    return $result;
  } 
}
?>