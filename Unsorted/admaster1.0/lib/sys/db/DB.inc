<?php 
class DB 
{         
    var $conn = false;
    var $host,
    	$name,
    	$user,
    	$pswd;
    
    var $query   = "",
    	$numRows = 0,
    	$result  = false;
    	
    var $recordSet;
    	
    function DB() 
    { 
        global $DB_HOST,
        	   $DB_NAME,
        	   $DB_USER,
        	   $DB_PSWD;
        
        $this->conn = @mysql_connect($DB_HOST, $DB_USER, $DB_PSWD);
          	 
        if (!$this->conn)
        {
			new Debug ("Can not connect to database in $DB_HOST", "DB", "DB", "ERROR", "SYS");
			return false;
		}
		
		$db = false;
		$db = @mysql_select_db($DB_NAME);
        if (!$db)
        {	
			new Debug ("Can not connect to database $DB_NAME", "DB", "DB", "ERROR", "SYS");
			return false;
		}		
		
		$this->recordSet = array ();
		
        $this->host = $DB_HOST;
        $this->name = $DB_NAME;
        $this->user = $DB_USER;
        $this->pswd = $DB_PSWD;
	}
	
    function connect ($DB_HOST, $DB_USER, $DB_PSWD, $DB_NAME)
    {
    	if ($this->conn && !$this->close ())
			return false;
        
        $this->conn = @mysql_connect($DB_HOST, $DB_USER, $DB_PSWD);
        if (!$this->conn)
        {
			new Debug ("Can not connect to database in $DB_HOST", "DB", "connect", "ERROR", "SYS");
			return false;
		}
		
		$db = false;
		$db = @mysql_select_db($DB_NAME);
        if (!$db)
        {
			new Debug ("Can not connect to database $DB_NAME", "DB", "connect", "ERROR", "SYS");
			return false;
		}	
        
        $this->host = $DB_HOST;
        $this->name = $DB_NAME;
        $this->user = $DB_USER;
        $this->pswd = $DB_PSWD;
    	
    	return true;
    }
    
	function getRecords ()
	{
		if (!$this->conn || !$this->result)
			return false;
		
		return $this->recordSet; 
	}
	
	function getNumRows ()
	{
		if (!$this->conn || !$this->result)
			return false;
		
		return $this->numRows;
	}
	
	function close ()
	{
		if ($this->conn && !@mysql_close($this->conn))
		{
			new Debug ("Can not close db connection", "DB", "connect", "ERROR", "SYS");
			return false;		
		}
		
		$this->conn = false;
		return true;
	}
    
    function execute ($query) 
    { 
        $this->query = $query;
        $this->result = false;

        $result = @mysql_query($query);

        if (!$result) 
        {
			new Debug ("Can not execute query: $query", "DB", "connect", "ERROR", "SYS");
			return false;
        }
		
		$this->numRows = @mysql_num_rows($result); 

        for($i = 0; $i < $this->numRows; $i++)  
             $this->recordSet [$i] = @mysql_fetch_row($result); 
             
   		$this->result = true;
   		
        return true; 
    } 
 
    function getFields ($tableName)
    { 
    	if (!$this->conn)
    		return false;
            
    	$fields = @mysql_list_fields($this->name, $tableName);
    	
		if (!$fields)
		{
			new Debug ("Can get field names of db: ".$this->name, "DB", "connect", "ERROR", "SYS");
			return false;			
		}
		
		$numOfFields = @mysql_num_fields($fields);
	
		$names = array ();
		for ($i = 0; $i < $numOfFields; $i++) 
	  		$names [$i] = @mysql_field_name($fields, $i);

		return $names;
    }
    
    function lastID ()
    {
    	if (!$this->conn)
    		return false;
    		
    	return @mysql_insert_id (); 	
    }
} 
?>