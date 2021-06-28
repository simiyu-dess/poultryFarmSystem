<?php

class Database{
    // Initializing poultry database variables
    private $servername;
    private $username;
    private $password;
    private $dbname;

    // Method to connecct to the database 
    public function connect(){
        $this->servername = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->dbname = 'poultry_farm';
 
        $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        return $conn;
    }
}
?>