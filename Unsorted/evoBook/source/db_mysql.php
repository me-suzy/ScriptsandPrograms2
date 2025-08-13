<?php

class sqldb {
	var $scriptname = ""; //nama script ni

	
	var $user="";
	var $pass="";
	var $server="";
	var $database="";

	var $query_id = 0; // query id
	var $query_counter = 0;
	var $link_id = 0;
	var $use_persistent = "0";

	var $insert_into = "";
	var $insert_data = array();
	/* ----------------------------------------------------------- */
	function start($server,$user,$password,$database)
	{
	  $this->server   = $server;
	  $this->user     = $user;
	  $this->pass = $password;
	  $this->database = $database;

	  $this->connect();
	}

	function connect()
	{
		
		if ($this->use_persistent == 0)
		{
			$this->link_id = mysql_connect($this->server,$this->user,$this->pass);
		}
		else
		{
			$this->link_id = mysql_pconnect($this->server,$this->user,$this->pass);
		}
		
		if (!$this->link_id)
		{
			$this->error("Connection to Database ".$this->database." Failed");
		}
		
		if(!@mysql_select_db($this->database, $this->link_id))
		{
			$this->error("mySQL database (".$this->database.")cannot be used");			
		}

		unset ($this->password);		
	}
	
	function show_all()
	{
		return "<br /><br /><b> Debug Mode - All Queries :</b><hr size=1> ".$this->query_show."<br />";
	}

	function query($string)
	{

		if (trim($string != ""))
		{
			$this->query_counter++;
			$this->query_show .= stripslashes($string)."<hr size='1'>";			
			$this->query_id = mysql_query($string);
		}

    	if (!$this->query_id) {
      		$this->error("mySQL Error on Query : ".$string);
    	}	

    	return $this->query_id;
  	}

	function query_insert()
	{
		if ( $this->insert_into && (is_array($this->insert_data)) )
		{
			$this->query_counter++;
			$this->query_show .= stripslashes($string)."<hr size='1'>";			

			foreach ($this->insert_data as $key => $val)
			{
				$value .= $key."='".$val."',";
			}
			
			if ( (substr($value,-1)) == "," )
			{
				$value = substr($value,0,-1);
			}

			$this->query_id = mysql_query("INSERT INTO ".$this->insert_into." SET $value");
		}

    	if (!$this->query_id) {
      		$this->error("mySQL Error on Query : ".$string);
    	}	

    	return $this->query_id;
  	}

	function fetch_array($query_id=-1)
	{
		if ($query_id != -1)
		{
			$this->query_id = $query_id;
		}

    	$this->result = mysql_fetch_array($this->query_id);
   		return $this->result;
	}	

	function free_result($query_id=-1)
	{
    	if ($query_id != -1)
		{
      		$this->query_id = $query_id;
    	}

    	return @mysql_free_result($this->query_id);
  	}
	
	function query_once($query_string)
	{
    	$this->query($query_string);
    	$get = $this->fetch_array($this->query_id);
		$this->free_result($this->$query_id);
		return $get;
  	}

	function num_rows($query_id=-1)
	{
		if ($quert_id != -1) {
			$this->query_id=$query_id;
		}

		return mysql_num_rows($this->query_id);
	}

	function insert_id()
	{
    	return mysql_insert_id($this->link_id);
  	}

	function get_errordesc()
	{
    	$this->error=mysql_error();
    	return $this->error;
  	}

  	function get_error_no()
	{
    	$this->errorno=mysql_errno();
    	return $this->errorno;
  	}

	function close()
	{
		mysql_close();
	}

	function error($msg)
	{
		global $_SERVER,$root;
    	
		$this->error_desc=mysql_error();
    	$this->error_no=mysql_errno();

		if ($this->report != 1)
		{
			$the_error = "<b>WARNING!</b><br />";
			$the_error .= "DB Error $this->scriptname: $msg <br /> Some more usefull information you might want to know: <br />";
			$the_error .= "<li> Mysql Error : $this->error_desc";
			$the_error .= "<li> Mysql Error no # : $this->error_no";
			$the_error .= "<li> Date : ".date("F j, Y, g:i a");
			$the_error .= "<li> Referer: ".$_SERVER['HTTP_REFERER'];
			$the_error .= "<li>Script: ".$_SERVER['REQUEST_URI'];
	  

			echo $the_error;
			die();
		}
		else
		{
			$this->report_error = $msg;
		}
    }




} // end of class
?>