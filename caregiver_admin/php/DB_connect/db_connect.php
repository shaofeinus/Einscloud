<?php

class DB_CONNECT {
	
	public $conn;

    function connect() {
        require_once "db_config.php";
        $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
		if(!$this->conn) {
			die("Connection failed: " . mysqli_connect_error());
		}
        return $this->conn;
    }

    function close() {
		if($this->conn) {
			mysqli_close($this->conn);
		}
	}
}
?>