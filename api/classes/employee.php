
<?php
class Employee {
  private $connection;
  private $table_name = "employees";

  public $employee_id;
  public $first_name;
  public $last_name;
  public $username;
  public $password;
  public $title_id;
  public $title;
  public $phone;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all employees
	public function get_all($employee_id = '') {
    $sql = "";
    if ($employee_id) {
      $sql = " WHERE employees.employee_id = :employee_id";
    }
    $query = "SELECT
                employee_id,
                first_name, 
                last_name, 
                username,
                password,
                titles.title_id,
                titles.name as title,
                phone
		          FROM " . $this->table_name . 
              " INNER JOIN titles ON titles.title_id = employees.title_id" . 
                  $sql .
		          " ORDER BY
		              last_name";
    $result = $this->connection->prepare($query);
    $result->bindParam(':employee_id', $employee_id);
    $result->execute();
  
    return $result;
	}

  //find an employee that matches a username
  public function get_employee_by_username($username) {
    $sql = "";
    if ($username) {
      $sql = " WHERE employees.username = :username";
    }
    $query = "SELECT
                employee_id,
                first_name, 
                last_name, 
                username,
                password,
                titles.title_id,
                titles.name as title,
                phone
              FROM " . $this->table_name . 
              " INNER JOIN titles ON titles.title_id = employees.title_id" . 
                  $sql .
              " ORDER BY
                  last_name";
    $result = $this->connection->prepare($query);
    $result->bindParam(':username', $username);
    $result->execute();
  
    return $result;
  }

  //update existing employee
  public function update($employee_id) {
    $first_name = filter_var($this->first_name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($this->last_name, FILTER_SANITIZE_STRING);
    $password = filter_var($this->password, FILTER_SANITIZE_STRING);
    $title_id = filter_var($this->title_id, FILTER_SANITIZE_STRING);
    $phone = filter_var($this->phone, FILTER_SANITIZE_STRING);

    $query = "UPDATE
                " . $this->table_name . "
              SET
                  first_name = :first_name,
                  last_name = :last_name,
                  password = :password,
                  title_id = :title_id,
                  phone = :phone
              WHERE
                  employee_id = :employee_id";

    $result = $this->connection->prepare($query);
    $result->bindParam(':employee_id', $employee_id);
    $result->bindParam(':first_name', $first_name);
    $result->bindParam(':last_name', $last_name);
    $result->bindParam(':password', $password);
    $result->bindParam(':title_id', $title_id);
    $result->bindParam(':phone', $phone);
    $result->execute();

    return $result;
  }

  //create new employee
  public function create() {
    $this->password = filter_var($this->password, FILTER_SANITIZE_STRING);
    $hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
    $first_name = filter_var($this->first_name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($this->last_name, FILTER_SANITIZE_STRING);
    $username = filter_var($this->username, FILTER_SANITIZE_STRING);
    $title_id = filter_var($this->title_id, FILTER_SANITIZE_STRING);
    $phone = filter_var($this->phone, FILTER_SANITIZE_STRING);
    $created = filter_var($this->created, FILTER_SANITIZE_STRING);

    $query = "INSERT INTO
                " . $this->table_name . "
                (first_name, last_name, username, password, title_id, phone, created)
              VALUES (:first_name, :last_name, :username, :password, :title_id, :phone, :created)";

    $result = $this->connection->prepare($query);
    $result->bindParam(':first_name', $first_name);
    $result->bindParam(':last_name', $last_name);
    $result->bindParam(':username', $username);
    $result->bindParam(':password', $hashed_password);
    $result->bindParam(':title_id', $title_id);
    $result->bindParam(':phone', $phone);
    $result->bindParam(':created', $created);
    $result->execute();

    return $result;
  }  

  //delete employee
  public function delete($employee_id) {
    $query = "DELETE FROM
                " . $this->table_name . "
                WHERE
                  employee_id = :employee_id";

    $result = $this->connection->prepare($query);
    $result->bindParam(':employee_id', $employee_id);
    $result->execute();

    return $result;
  }   
}
?>