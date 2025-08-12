<?php



class MysqlException extends Exception {
  public $backtrace;
  public function __construct($message=false, $code=false) {
	$this->message="";
    if($message) {
		$this->message="<pre>".$message;
    }
    $this->message .= mysql_error();

    if(!$code) {
      $this->code = mysql_errno();
    }
    $this->backtrace = debug_backtrace();
  }
}

interface DB_Connection {
  public function prepare($query);
  public function execute($query);
}

interface DB_Statement {
  public function execute();
  public function bind_param($key, $value);
  public function fetch_row();
  public function fetch_assoc();
  public function fetchall_assoc();
}

class DB_Mysql implements DB_Connection 
{
  protected $user;
  protected $pass;
  protected $dbhost;
  protected $dbname;
  protected $dbh;

  public function __construct($user=NULL, $pass=NULL, $dbhost=NULL, $dbname=NULL) 
  {

    if($user) {
        $this->user= $user;
    }
    else {
        $this->user = confGet('DB_USERNAME');
    }

    if($pass) {
        $this->pass= $pass;
    }
    else {
        $this->pass = confGet('DB_PASSWORD');
    }
    if($dbhost) {
        $this->dbhost= $dbhost;
    }
    else {
        $this->dbhost = confGet('HOSTNAME');
    }
    
    if($dbname) {
        $this->dbname= $dbname;
    }
    else {
        $this->dbname = confGet('DB_NAME');
    }
    
    
  }

  protected function connect() 
  {
    $this->dbh = mysql_pconnect($this->dbhost, $this->user, $this->pass);
    if(!is_resource($this->dbh)) {
      throw new MysqlException;
    }
    if(!mysql_select_db($this->dbname, $this->dbh)) {
      throw new MysqlException;
    }
  }
  
    public function execute($query) 
    {
        if(!$this->dbh) {
            $this->connect();
        }
        $ret = mysql_query($query, $this->dbh);
        if(!$ret) {
            throw new MysqlException;
        }
        else if(!is_resource($ret)) {
            return TRUE;
        } 
        else {
            $stmt = new DB_MysqlStatement($this->dbh, $query);
            $stmt->result = $ret;
            return $stmt;
        }
    }

	public function prepare($query) 
	{
		if(!$this->dbh) {
		  $this->connect();
		}
		return new DB_MysqlStatement($this->dbh, $query);
	}


    /**
    * check if database is online and return current version
    * 
    * this should be, if user-id is not set (checking always might
    * be too performance intensive)
    */  
	public   function getVersion() 
	{
        $this->dbh = mysql_pconnect($this->dbhost, $this->user, $this->pass);
        if(!is_resource($this->dbh)) {

            ### can't connect db... ###
            return NULL;
        }
        if(!mysql_select_db($this->dbname, $this->dbh)) {

            ### can't select... ###
            return NULL;
        }
        
        ### get version ###
		$prefix= confGet('DB_TABLE_PREFIX');

        $result= mysql_query("select * from {$prefix}db where updated is NULL");
        if($result) {
            return mysql_fetch_assoc($result);
        }
        else {
            return NULL;
        }
	}
  
}

class DB_MysqlStatement implements DB_Statement 
{
	public $result;
	public $binds;
	public $query;
	public $dbh;
	public function __construct($dbh, $query) {
		$this->query = $query;
		$this->dbh = $dbh;
	
		if(!is_resource($dbh)) {
			throw new MysqlException("Not a valid database connection");
		}
	}
	public function bind_param($ph, $pv) {
	$this->binds[$ph] = $pv;

	return $this;
}

  #-------------------------------------------------
  # execute()
  # - requires list of params
  #-------------------------------------------------
  public function execute() {
        $args = func_get_args();
        #if(count(func_get_args())) {
        #    throw new Exception("pass values as array");
        #}

        #--- bind args in member with 1 ----
        $this->binds=array();
        foreach($args as $index => $name) {
            $this->binds[$index + 1] = $name;
        }

        $cnt = count($args);
        $query = $this->query;

        foreach ($this->binds as $ph => $pv) {
            #$query = @str_replace(":$ph", , $query);
            $query= preg_replace("/$ph\b/", "'".mysql_real_escape_string($pv)."'", $query);
        }
        $this->result = mysql_query($query, $this->dbh);
        if(!$this->result) {
            throw new MysqlException("Querry=".$query."\n");
        }

        #--- cound traffic for debug-output ----
        global $DB_ITEMS_LOADED;
        $DB_ITEMS_LOADED+= count($this->result);

        return $this;
    }

    public function fetch_row()
    {
        if(!$this->result) {
          throw new MysqlException("Query not executed");
        }
        return mysql_fetch_row($this->result);
    }

    public function fetch_assoc()
    {
        $row=array();

        if($res=mysql_fetch_assoc($this->result)) {
            foreach($res as $key=>$value) {
                $row[$key]= stripslashes(stripslashes(stripslashes($value)));
            }
        }
        return $row;
    }

    public function fetchall_assoc()
    {
        $retval = array();
        while($row = $this->fetch_assoc()) {
            foreach($row as $key=>$value) {
                $row[$key]= stripslashes(stripslashes(stripslashes($row[$key])));
            }
            $retval[] = $row;

            #--- cound traffic for debug-output ----
            global $DB_ITEMS_LOADED;
            $DB_ITEMS_LOADED+= count($row);

        }
        return $retval;
    }
}

class DB_Result {
  protected $stmt;
  protected $result = array();
  private $rowIndex = 0;
  private $currIndex = 0;
  private $done = false;

  public function __construct(DB_Statement $stmt)
  {
    $this->stmt = $stmt;

  }
  public function first()
  {
    if(!$this->result) {
      $this->result[$this->rowIndex++] = $this->stmt->fetch_assoc();
    }
    $this->currIndex = 0;
    return $this;
  }
  public function last()
  {
    if(!$this->done) {
      array_push($this->result, $this->stmt->fetchall_assoc());
    }
    $this->done = true;
    $this->currIndex = $this->rowIndex = count($this->result) - 1;
    return $this;
  }
  public function next()
  {
    if($this->done) {
      return false;
    }
    $offset = $this->currIndex + 1;
    if(!$this->result[$offset]) {
      $row = $this->stmt->fetch_assoc();
      if(!$row) {
        $this->done = true;
        return false;
      }
      $this->result[$offset] = $row;
      ++$this->rowIndex;
      ++$this->currIndex;
      return $this;
    }
    else {
      ++$this->currIndex;
      return $this;
    }
  }
  public function prev()
  {
    if($this->currIndex == 0) {
      return false;
    }
    --$this->currIndex;
    return $this;
  }
  public function __get($value)
  {
    if(array_key_exists($value, $this->result[$this->currIndex])) {
      return $this->result[$this->currIndex][$value];
    }
  }
}

require_once(dirname(__FILE__)."/../".confGet('DIR_SETTINGS').confGet('FILE_DB_SETTINGS'));



class DB_Mysql_Debug extends DB_Mysql {
  protected $elapsedTime;
  public function execute($query) {
    // set timer;
    parent::execute($query);
    // end timer;
  }
  public function getElapsedTime() {
    return $this->$elapsedTime;
  }
}

?>
