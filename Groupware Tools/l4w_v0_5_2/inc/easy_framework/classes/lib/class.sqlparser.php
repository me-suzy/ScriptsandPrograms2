<?php

 /**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.sqlparser.php,v 1.8 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
 class simple_sqlparser {
	
    var $query      = null;
    var $filter     = null;
	var $type       = null;
	var $where      = null;
	var $group_by   = null;
	var $having     = null;
	var $order_by   = null;
	var $rest       = null;
	
	function handle_token ($str,$token) {
	    //echo "token ".$str.", ".$token."<br>";
	    $token_exists = false;
	    
	    // search_token should be written in lower case
	    $search_token = " ".strtolower($token)." ";
	    $str          = eregi_replace ($search_token,$search_token,$str); 
		//echo "looking for: '".$search_token."' in ###<pre>".$str."</pre>### ";
	    if (substr_count($str,$search_token) > 0) {
	        //echo "yes<br>";
	        $token_exists = true;   
	    }
	    else {
	    	//echo "no<br>";	
	    } 	
	    
	    if ($token_exists) {
	        //echo "Found token: ".$token."<br>";
	        $tmp = explode ($search_token, $str);
	        $token_value = array_pop ($tmp); 
	        //echo "token: ".$token.", ".$token_value."<br>";
	        $str   = implode ('',$tmp); 
	        //echo $str;
	        return array (trim($token_value), trim($str));
	    }
        return array (null, $str); 
    }
    
	function simple_sqlparser ($statement, $filter = null) {
	    //echo $statement."<br><br>";
	    // -- straighten text ---------------------------------------
	    $query = str_replace (array (chr(10), chr(13), chr(9)), " ", $statement);
	    //$query = strtolower ($query);
	    $tmp   = explode (" ", $query);
	    $query = '';
	    foreach ($tmp AS $key => $chunk) {
	    	//echo "C: ".var_dump($chunk)."<br>";
	        if (trim($chunk) != '') {
    	        $query .= $chunk." ";
	        }        
	    }
	    $this->query  = $query;
	    $this->filter = $filter;
	    $rest = $this->query;

	    list ($this->order_by, $rest) = $this->handle_token ($rest, "order by");
	    //echo $this->order_by."<br><br>";
	    list ($this->having,   $rest) = $this->handle_token ($rest, "having");
	    list ($this->group_by, $rest) = $this->handle_token ($rest, "group by");
	    list ($this->where,    $rest) = $this->handle_token ($rest, "where");

        // Handle filter
        if (!is_null($this->filter)) {
            $this->where = "(".$this->where.") AND ($this->filter)";    
        }     

        $this->rest = $rest;
	}
	
	function omitt_order () {$this->order_by = null;}
	
	function set_order ($column, $direction, $add = false) {
	    if ($add || $this->order_by == "")
		    $this->order_by = $column." ".$direction;	    
	    else
		    $this->order_by .= ", ".$column." ".$direction;
	}
	
	function add_where_clause ($where_sql) {
	 
	    if (is_null($this->where))
	        $this->where = $where_sql;
	    else 
	        $this->where = $this->where." AND ($where_sql)";
	}
	
	function substitute ($key, $value) {
	    $this->rest = str_replace($key, $value, $this->rest);
	    if ($this->where != null)
    	    $this->where = str_replace($key, $value, $this->where);
	    if ($this->group_by != null)
    	    $this->group_by = str_replace($key, $value, $this->group_by);
	    if ($this->having != null)
    	    $this->having = str_replace($key, $value, $this->having);
	    if ($this->order_by != null)
    	    $this->order_by = str_replace($key, $value, $this->order_by);
    
	}
	
	function get_query () {
	    $ret = $this->rest." ";
	    if ($this->where != null)
    	    $ret .= " WHERE ".$this->where." ";    
	    if ($this->group_by != null)
    	    $ret .= " GROUP BY ".$this->group_by." ";    
	    if ($this->having != null)
    	    $ret .= " HAVING ".$this->having." ";    
	    if ($this->order_by != null)
    	    $ret .= " ORDER BY ".$this->order_by;  
    	//echo "(".$ret.")";
    	return $ret; 
	}
	
	function dump () {
	    $ret = $this->query."<br>=====================<br>";
   	    $ret .= "<b>rest:    </b>".$this->rest."<br>";    
	    if ($this->where != null)
    	    $ret .= "<b>where:    </b>".$this->where."<br>";    
	    if ($this->group_by != null)
    	    $ret .= "<b>group by: </b>".$this->group_by."<br>";    
	    if ($this->having != null)
    	    $ret .= "<b>having :  </b>".$this->having."<br>";    
	    if ($this->order_by != null)
    	    $ret .= "<b>order by: </b>".$this->order_by."<br>";    
	    return $ret;
	}
	
	function getOrderDirection () {
	    if (is_null($this->order_by)) return "";
	    if (substr_count(" ".$this->order_by." ", " desc ") > 0)
	        return "desc";
	    else  
	        return "asc";
	}

}


?>
