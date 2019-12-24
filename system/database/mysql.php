<?php
final class MySQL {
	private $connection;
	
	public function __construct($hostname, $username, $password, $database) {
		if (!$this->connection = mysqli_connect($hostname, $username, $password)) {
      		exit('Error: Could not make a database connection using ' . $username . '@' . $hostname);
    	}

    	if (!mysqli_select_db($this->connection, $database)) {
      		exit('Error: Could not connect to database ' . $database);
    	}
		
		mysqli_query($this->connection, "SET NAMES 'utf8'");
		mysqli_query($this->connection, "SET CHARACTER SET utf8");
		mysqli_query($this->connection, "SET CHARACTER_SET_CONNECTION=utf8");
		mysqli_query($this->connection, "SET SQL_MODE = ''");
  	}
		
  	public function query($sql) {
		  
		$resource = mysqli_query($this->connection, $sql);
		
		if ($resource) {
			if ($resource instanceof mysqli_result) {
				$i = 0;
    	
				$data = array();
		
				while ($result = mysqli_fetch_assoc($resource)) {
					$data[$i] = $result;
    	
					$i++;
				}
				
				mysqli_free_result($resource);
				
				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;
				
				unset($data);

				return $query;	
    		} else {
				return TRUE;
			}
		} else {
      		exit('Error: ' . mysql_error($this->connection) . '<br />Error No: ' . mysql_errno($this->connection) . '<br />' . $sql);
    	}
  	}
	
	public function escape($value) {
		return mysqli_real_escape_string($this->connection, $value);
	}
	
  	public function countAffected() {
    	return mysqli_affected_rows($this->connection);
  	}

  	public function getLastId() {
    	return mysqli_insert_id($this->connection);
  	}	
	
	public function __destruct() {
		mysqli_close($this->connection);
	}
}
?>
