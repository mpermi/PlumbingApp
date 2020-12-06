
<?php
class Job {
  private $connection;
  private $table_name = "Jobs";

  public $job_id;
  public $date;
  public $customer_id;
  public $customer_first_name;
  public $customer_last_name;
  public $customer_phone;
  public $customer_address1;
  public $customer_address2;
  public $customer_city;
  public $customer_state;
  public $customer_zipcode;
  public $issue;
  public $employee_id;
  public $employee_first_name;
  public $employee_last_name;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all jobs
	public function get_all($job_id = '') {
    $sql = "";
    if ($job_id) {
      $sql = " WHERE jobs.job_id = :job_id";
    }
    $query = "SELECT
                jobs.job_id,
                jobs.date, 
                jobs.customer_id, 
                customers.first_name as customer_first_name,
                customers.last_name as customer_last_name,
                customers.phone as customer_phone,
                addresses.address1 as customer_address1,
                addresses.address2 as customer_address2,
                addresses.city as customer_city,
                addresses.state as customer_state,
                addresses.zipcode as customer_zipcode,
                jobs.issue,
                jobs.employee_id,
                employees.first_name as employee_first_name,
                employees.last_name as employee_last_name
		          FROM " . $this->table_name . 
              " INNER JOIN customers ON customers.customer_id = jobs.customer_id
                INNER JOIN employees ON employees.employee_id = jobs.employee_id
                LEFT JOIN addresses ON addresses.address_id = customers.address_id" .
                  $sql .
		          " ORDER BY
		              jobs.date";
    $result = $this->connection->prepare($query);
    $result->bindParam(':job_id', $job_id);
    $result->execute();
  
    return $result;
	}

  //create new job
  public function create() {
    $date = filter_var($this->date, FILTER_SANITIZE_STRING);
    $customer_id = filter_var($this->customer_id, FILTER_SANITIZE_STRING);
    $issue = filter_var($this->issue, FILTER_SANITIZE_STRING);
    $employee_id = filter_var($this->employee_id, FILTER_SANITIZE_STRING);
    $created = filter_var($this->created, FILTER_SANITIZE_STRING);

    $query = "INSERT INTO
                " . $this->table_name . "
                (date, customer_id, issue, employee_id, created)
              VALUES (:date, :customer_id, :issue, :employee_id, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':date', $date);
    $result->bindParam(':customer_id', $customer_id);
    $result->bindParam(':issue', $issue);
    $result->bindParam(':employee_id', $employee_id);
    $result->bindParam(':created', $created);
    $result->execute();

    return $result;
  }

  //update existing job
  public function update($job_id) {
    $date = filter_var($this->date, FILTER_SANITIZE_STRING);
    $customer_id = filter_var($this->customer_id, FILTER_SANITIZE_STRING);
    $issue = filter_var($this->issue, FILTER_SANITIZE_STRING);
    $employee_id = filter_var($this->employee_id, FILTER_SANITIZE_STRING);

    $query = "UPDATE
                " . $this->table_name . "
              SET
                  date = :date,
                  customer_id = :customer_id,
                  issue = :issue,
                  employee_id = :employee_id
              WHERE
                  job_id = :job_id";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':job_id', $job_id);
    $result->bindParam(':customer_id', $customer_id);
    $result->bindParam(':issue', $issue);
    $result->bindParam(':employee_id', $employee_id);
    $result->bindParam(':date', $date);
    $result->execute();

    return $result;
  }

    //delete job
  public function delete($job_id) {
    $query = "DELETE FROM
                " . $this->table_name . "
                WHERE
                  job_id = :job_id";

    $result = $this->connection->prepare($query);
    $result->bindParam(':job_id', $job_id);
    $result->execute();

    return $result;
  } 
}
?>