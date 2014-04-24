<?php 

class Database{
	private static $instance;
	private static $connection;
	private $result;
	
	
	private function __construct(){
		
	}
	
	public static function getInstance(){
		if(!self::$instance){
			self::$instance = new self();
		}
		return self::$instance; 
	}
	
	public function connect(){
		self::$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		if(!self::$connection)
		{
			echo "Cannot Connect to the Database"; exit;
			return false;
		}
		else
		{
			return true;
		}
	}
	
	// Will execute the query ... @param  $sql - contains the query
	public function doQuery($sql){
		//$Esql = $this->escapeString($sql);
		$this->result = mysqli_query(self::$connection, $sql);
		//print($sql."<br>");
	}
	
	public function escapeString($sql){
		return mysqli_real_escape_string(self::$connection, $sql);	
	}
	
	// It will return the last insered ID from the corresponding table insertion.
	public function getInsertID(){
		return mysqli_insert_id(self::$connection);
	}
	
	// It will return a row of record based on query ...
	public function getResultRow(){
		$obj = "No result.";
		if($this->result){
			$obj = mysqli_fetch_assoc($this->result);
		}		
		return $obj;
	}
	
	// This function will return the the whole records bases on query ...
	public function getResultSet(){
		$rSet = "No result.";
		$rs1 = array();

		if($this->result){
			while($rs = mysqli_fetch_assoc($this->result)){ 
				array_push($rs1, $rs);
			}
			if(count($rs1) > 0){
				$rSet = $rs1;
			}
		}
		return $rSet;
	}
	
	// This function will return the no.of rows of results from the query execution ...
	public function getResultNumRows(){
		return mysqli_affected_rows(self::$connection);
	}

	 public function NumRows()
    	 {		
		   if ($this->result)
		   {
			return mysqli_num_rows($this->result);
		   }
         }

	 public function FetchValue()
	 {
		if($this->result)
		{
			if (is_array($tmp_arr = mysqli_fetch_assoc($this->result)))
			{
			    return array_shift($tmp_arr);
			}
		}
		return FALSE;
		
	 }

	
}
?>
