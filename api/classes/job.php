
<?php
class Job {
  private $connection;
  private $table_name = "Jobs";

  public $job_id;
  public $date;
  public $customer_id;
  public $customer_first_name;
  public $customer_last_name;
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
                jobs.issue,
                jobs.employee_id,
                employees.first_name as employee_first_name,
                employees.last_name as employee_last_name
		          FROM " . $this->table_name . 
              " INNER JOIN customers ON customers.customer_id = jobs.customer_id
                INNER JOIN employees ON employees.employee_id = jobs.employee_id" .
                  $sql .
		          " ORDER BY
		              jobs.date DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':job_id', $job_id);
    $result->execute();
  
    return $result;
	}

  //create new job
  public function create() {
    $query = "INSERT INTO
                " . $this->table_name . "
                (date, customer_id, issue, employee_id, created)
              VALUES (:date, :customer_id, :issue, :employee_id, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':date', filter_var($this->date, FILTER_SANITIZE_STRING));
    $result->bindParam(':customer_id', filter_var($this->customer_id, FILTER_SANITIZE_STRING));
    $result->bindParam(':issue', filter_var($this->issue, FILTER_SANITIZE_STRING));
    $result->bindParam(':employee_id', filter_var($this->employee_id, FILTER_SANITIZE_STRING));
    $result->bindParam(':created', filter_var($this->created, FILTER_SANITIZE_STRING));
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