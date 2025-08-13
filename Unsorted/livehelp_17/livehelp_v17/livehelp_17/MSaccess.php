<?

Class MS_options
{
	var $CONN   = "";

	function init ()
	{	
		global $dbpath;		

        $conn = new COM("ADODB.Connection") or die("Cannot start ADO");
        $conn->Open("DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=$dbpath");

		$this->CONN = $conn;
		return true;
	}

	function select($sql="", $column="")
	{
	  $conn = $this->CONN;
 	  if(!eregi("^select",$sql))
	  {
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
	  }
	  // Recordset
	  $rs = $conn->Execute($sql);    
	  
	  // get the columns
	  $num_columns = $rs->Fields->Count();

          // make associative array.
	  $rowcount = 0;
          while (!$rs->EOF)
          {			 
	    for($i=0;$i<count($fieldnames); $i++){
	       $myobject = $rs->Fields($i);            
	       $myfield = $myobject->name;
	       $myfield_val  = $myobject->value;                
               if ($myfield_val != ""){
            	 $data_array[$myfield] = $myfield_val;
               }   
            }  
	   $data[$rowcount] = $data_array;
	   $rowcount = $rowcount + 1;
	  } 	
	return $data;
	}

	function insert ($sql="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^insert",$sql))
		{
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
		}
		if(empty($this->CONN))
		{
			echo "<H2>No connection!</H2>\n";
			return false;
		}
		$conn = $this->CONN;
 	        $rs = $conn->Execute($sql);   
		return $rs;
	}

	function sql_query ($sql="")
	{
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
 	        $rs = $conn->Execute($sql);   
		return $rs;
	}
	
	function close_connect(){
          $this->CONN->Close();
          $this->CONN->Release();
          $this->CONN = null;
	}
	
}	//	End Class
?>
