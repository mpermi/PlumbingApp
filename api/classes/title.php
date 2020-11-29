
<?php
class Title {
  private $connection;
  private $table_name = "titles";

  public $title_id;
  public $name;

  public function __construct($db){
    $this->connection = $db;
  }

	//get all titles
	public function get_all($title_id = '') {
    $sql = "";
    if ($title_id) {
      $sql = " WHERE titles.title_id = :title_id";
    }
    $query = "SELECT
                title_id,
                name
		          FROM " . $this->table_name . 
		          " ORDER BY
		              name DESC";
    $result = $this->connection->prepare($query);
    $result->bindParam(':title_id', $title_id);
    $result->execute();
  
    return $result;
	}

  //create new title
  public function create() {
    $name = filter_var($this->name, FILTER_SANITIZE_STRING);
    $created = filter_var($this->created, FILTER_SANITIZE_STRING);

    $query = "INSERT INTO
                " . $this->table_name . "
                (name, created)
              VALUES (:name, :created)";
  
    $result = $this->connection->prepare($query);
    $result->bindParam(':name', $name);
    $result->bindParam(':created', $created);
    $result->execute();

    return $result;
  }
}
?>