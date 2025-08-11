<?php
//modified 29 jan 2005

class DB_sql{
	
	function DB_sql($host,$user,$pass,$db,$type,$debug=false){
		//$this->config = $config;
		$this->host = $host;
		$this->user = $user;
		$this->password = $pass;
		$this->db = $db;
		$this->debug = $debug;
		$this->db_type = $type;

	}

	function exec($query){
		if($this->debug)echo "<br>".$query;
		switch ($this->db_type){
			case "mysql":return $this->mysql_exec($query);break;
			case "pgsql":return $this->pg_exec($query);break;
		}
	}	
	
	function select($query){
		$returned = array();
		$result = $this->exec($query) or print("<br><b>Error:</b> unable to execute query $query");
		switch ($this->db_type){
			case "mysql":while($line = mysql_fetch_array($result))$returned[] = $line;break;
			case "pgsql":while($line = pg_fetch_array($result))$returned[] = $line;break;
		}
		return $returned;
	}

	//------------------------------------------
	function mysql_exec($query){
		$link=mysql_connect($this->host,$this->user,$this->password) or die("<br><b>Error:</b> unable to connect db server". mysql_error());
		mysql_select_db("$this->db") 
			or print("<br><b>Error:</b> unable to select db $this->db". mysql_error());
		$result=mysql_query($query) 
			or print("<br><b>Error:</b> unable to execute query: [$query]". mysql_error());
		return $result;
	}

	function pg_exec($query){			
		$link=pg_connect("host=$this->host user=$this->user password=$this->password dbname=$this->db") 
			or print("<br><b>Error:</b> unable to connect db server");
		$result=pg_query($query) 
			or print("<br><b>Error:</b> unable to execute query $query");
		return $result;
	}	
	
	function mysql_select($query){
		$returned = array();
		$result = $this->exec($query) or print("<br><b>Error:</b> unable to execute query $query");
		while($line = mysql_fetch_array($result))
			$returned[] = $line;	
		return $returned;
	}

	function pg_select($query){
		$result = $this->exec($query);
		while($line = pg_fetch_array($result))
			$returned[] = $line;	
		return $returned;
	}
	
	function usedump($_file){
		$fp = fopen($_file,'r');
		$query = '';
		while ($line=fgets($fp,1024)){
			//echo '<br>SOURCE:'.($line);
			if( (substr($line,0,2) == '  ' && $query == '')
				|| substr($line,0,2) == '--' 
				|| substr($line,0,1) == '#'
				){
				}else{
					$query .= $line;
				}
			
			if(strstr($line,';')){
				$query = trim($query);
				//echo '<br>['.$query.']<br>';
				$this->exec("$query");
				/*$fp_ = fopen($_file.'_out','a');
				fputs($fp_,"\n".$query);
				fclose($fp_);*/
				$query = '';
			}
		}
		fclose($fp);
	}
	
	function test($table,$field,$value){
		//echo '<hr>test($table,$field,$value):'."test($table,$field,$value)";
		$sql = "SELECT $field FROM $table";
		$result = $this->select($sql);
		return ($result[0][0] == $value)?true:false;
	}	
}
?>
