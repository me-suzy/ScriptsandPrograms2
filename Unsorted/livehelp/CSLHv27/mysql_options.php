<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/

Class MySQL_optionsVars
{
	
	var $CONN   = "";
        var $TRAIL = array();
	var $HITS = array();
  
	function error($text)
	{
		$no = mysql_errno();
		$msg = mysql_error();
		echo "[$text] ( $no : $msg )<BR>\n";
		exit;
	}

	function init($server,$user,$pass)
	{
		$conn = mysql_connect($server,$user,$pass); 		
		if(!$conn) {
			$this->error("Connection attempt failed");
		}
		$this->CONN = $conn;
		return true;
	}

        function selectdb($dbase)
        {
            $conn = $this->CONN; 	
            if(!mysql_select_db($dbase,$conn)) {
		 $this->error("Dbase Select failed");
	    }
        	
        }

	function select($sql="", $column="")
	{
		if(empty($sql)) { return false; }
		if(!eregi("^select",$sql))
		{
			echo "<H2>Wrong function silly!</H2>\n";
			return false;
		}
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) {
			$this->error($sql);
			return false;
		}
		$count = 0;
		$data = array();
		while ( $row = mysql_fetch_array($results))
		{
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($results);
		return $data;
	}

	function insert($sql="")
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
		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>No results!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			return false;
		}
		$results = mysql_insert_id();
		return $results;
	}

	function sql_query($sql="")
	{
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			return false;
		}
		return $results;
	}

	function sql_cnt_query($sql="")
	{
		if(empty($sql)) { return false; }
		if(empty($this->CONN)) { return false; }
		$conn = $this->CONN;
		$results = mysql_query($sql,$conn);
		if( (!$results) or (empty($results)) ) {
			mysql_free_result($results);
			return false;
		}
		$count = 0;
		$data = array();
		while ( $row = mysql_fetch_array($results))
		{
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($results);
		return $data[0][0];
	}

        function showdbs(){
           $count=0;
           $conn = $this->CONN;          
           $db_list = mysql_list_dbs($conn);
           while ($row = mysql_fetch_object($db_list)) {
              $data[$count] = $row->Database;
	      $count++;
           }
          return $data;	
        }	
        	
        function showtables($dbname){
          $conn = $this->CONN;
          $results = mysql_list_tables($dbname,$conn);
          $count = 0;
          if (!$results) {
             print "DB Error, could not list tables\n";
             print 'MySQL Error: ' . mysql_error();
             exit;
          }
    
          while ($row = mysql_fetch_row($results)) {
              $data[$count] = $row[0];
	      $count++;
          }

           mysql_free_result($results);
	  
          return $data;	
        }
	
	function close_connect(){
	   $conn = $this->CONN;		
	   mysql_close($conn);	
	}
}	//	End Class
?>
