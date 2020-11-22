<?php
class Database{
  
    private $hostname = "127.0.0.1";
    private $database = "ljbutler_plumbing";
    private $username = "maria";
    private $password = "";
    public $connection;
  
    //connect to the database
    public function connect(){
        $this->connection = null;
  
        try{
          $this->connection = new PDO("mysql:host=" . $this->hostname . ";dbname=" . $this->database, $this->username, $this->password);
          $this->connection->exec("set names utf8");
        }catch(PDOException $exception){
          echo "Error: " . $exception->getMessage();
        }
  
        return $this->connection;
    }
}
?>