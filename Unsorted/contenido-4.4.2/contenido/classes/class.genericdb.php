<?php

/**********************************************************************************
* File      :   $RCSfile: class.genericdb.php,v $
* Project   :   Contenido 
* Descr     :   Generic database abstraction functions
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   18.07.2003
* Modified  :   $Date: 2003/08/25 07:21:15 $
*
* © four for business AG, www.4fb.de
*
* This file is part of the Contenido Content Management System. 
*
* $Id: class.genericdb.php,v 1.4 2003/08/25 07:21:15 timo.hummel Exp $
***********************************************************************************/


/**
 * Class ItemCollection
 * Class for database based item collections
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 0.1
 * @copyright four for business 2003
 */
class ItemCollection {
	
	/**
	 * Storage of the source table to use for the information
     * @var string Contains the source table
     * @access private
	 */
	var $table;

	/**
	 * Storage of the primary key
     * @var string Contains the primary key of the source table
     * @access private
	 */
	var $primaryKey;
	
	/**
	 * DB_Contenido instance
     * @var object Contains the database object
     * @access private
	 */
	var $db;	
	
	/**
	 * Storage of the last error
     * @var string Contains the error string of the last error occured
     * @access private
	 */
	var $lasterror;	
	
	/**
     * Storage of all result items
     * @var string Contains all result items
     * @access private
     */
    var $objects;

	/**
     * Cache the result items
     * @var array Contains all cache items
     * @access private
     */
    var $cache;
    
	/**
     * @var int Lifetime in seconds
     * @access private
     */
    var $lifetime;
        
	/**
     * Constructor Function
	 * Note: Default lifetime is 10 seconds.
     * @param string $table The table to use as information source
     */
	function ItemCollection($table, $primaryKey, $lifetime = 10)
	{
		$this->db = new DB_Contenido;
		
		if ($table == "")
		{
			$classname = get_parent_class($this);
			die("Class $classname: No table specified. Inherited classes *need* to set a table");

		} 

		if ($primaryKey == "")
		{
			die("No primary key specified. Inherited classes *need* to set a primary key");
		} 
		
		$this->table = $table;
		$this->primaryKey = $primaryKey;
		
		$this->lifetime = $lifetime;
		 
	}
	
    /**
     * select ($where = "", $group_by = "", $order_by = "", $limit = "")
     * Selects all entries from the database and returns them as DBObject-objects
	 * to the user. Objects are loaded using their primary key.
	 * @param string $where Specifies the where clause.
	 * @param string $group_by Specifies the group by clause.
	 * @param string $order_by Specifies the order by clause.
	 * @param string $limit Specifies the limit by clause.
	 * @return array Array of DBObject-Objects
     */	
	function select ($where = "", $group_by = "", $order_by = "", $limit = "")
	{
		unset ($this->objects);
		
		if ($where == "")
		{
			$where = "";
		} else {
			$where = " WHERE " . $where;
		}
		
		if ($group_by != "")
		{
			$group_by = " GROUP BY ".$group_by;
		}
		
		if ($order_by != "")
		{
			$order_by = " ORDER BY ".$order_by;
		}
		
		if ($limit != "")
		{
			$limit = " LIMIT ".$limit;
		}
		
		$sql = "SELECT " . $this->primaryKey .
		       " FROM " . $this->table .
		       $where . $group_by . $order_by . $limit;

	    $this->db->query($sql);

	    if ($this->db->num_rows() == 0)
	    {
	    	return false;
	    } else {
	
			/* Store results in the objects array */
			while ($this->db->next_record())
			{
				$this->objects[$this->db->f($this->primaryKey)] = $this->loadItem($this->db->f($this->primaryKey));
			}
			
			/* Reset the counter to the first entry, if any. */
			if (is_array($this->objects))
			{
				reset($this->objects);
			}
				
	    }
	}


    /**
     * exists ($id)
     * Checks if a specific entry exists 
	 * @param integer $id The id to check for
	 * @return boolean true if object exists, false if not
     */
	function exists ($id)
	{
		$db = new DB_Contenido;
		
		$sql = "SELECT " .$this->primaryKey .
               " FROM " . $this->table .
			   " WHERE " . $this->primaryKey .
			   " ='" . $id . "'";
			   
		$db->query($sql);
		
		if ($db->next_record())
		{
			return true;
		} else {
			return false;
		} 
	}

    /**
     * next ()
     * Advances to the next item in the database. 
	 * @param none
	 * @return object The next object, or false if no more objects
     */
	function next ()
	{
		
		if (!is_array($this->objects))
		{
			return false;
		}
		
		if ((list($key, $value) = each ($this->objects)) == false)
		{
			return false;
		} else {
			return ($value);
		} 

		/*
		if (is_array($this->objects))
		{
			return ($this->objects
		if ($this->db->next_record())
		{
			return $this->loadItem($this->db->f($this->primaryKey));
		} else {
			return false;
		}
		*/
		
	}
	
    /**
     * count ()
     * Returns the amount of returned items
	 * @param none
	 * @return integer Number of rows
     */
	function count ()
	{
		return ($this->db->num_rows());
	}	
		
    /**
     * loadItem ($item)
     * Loads a single object from the database.
	 * Needs to be overridden by the extension class.
	 * @param variant $item Specifies the item to load
	 * @return object The newly created object
     */	
    function loadItem ($item)
    {
    	die("loadItem *must* be overridden by the extension class");
    }

	/**
     * create()
     * Creates a new item in the table and loads it afterwards.
     */
	function create()
	{
		/* Local new db instance since we don't want to kill our
           probably existing result set */
		$db = new DB_Contenido;
		
		$nextid = $db->nextid($this->table);
		$sql  = "INSERT INTO " .$this->table ." (";
		$sql .= $this->primaryKey . ") VALUES (". $nextid.")";
		
		$db->query($sql);
		return $this->loaditem($nextid);
	}	
	
	/**
     * delete()
     * Deletes an item in the table and loads it afterwards.
     */
	function delete($id)
	{
		/* Local new db instance since we don't want to kill our
           probably existing result set */
		$db = new DB_Contenido;
		
		$sql  = "DELETE FROM " .$this->table ." WHERE ";
		$sql .= $this->primaryKey . " = '". $id ."'";
		
		$db->query($sql);
		
		if ($db->affected_rows() == 0)
		{
			return false;
		} else {
			return true;
		}
	}	
}

/**
 * Class Item
 * Class for database based items
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 0.1
 * @copyright four for business 2003
 */
class Item {

	/**
	 * Storage of the source table to use for the user informations
     * @var string Contains the source table
     * @access private
	 */
	var $table;

	/**
	 * DB_Contenido instance
     * @var object Contains the database object
     * @access private
	 */
	var $db;	

	/**
	 * Primary key of the table
     * @var object Contains the database object
     * @access private
	 */
	var $primaryKey;
		
	/**
	 * Storage of the source table to use for the user informations
     * @var array Contains the source table
     * @access private
	 */
	var $values;	
	
	/**
	 * Storage of the fields which were modified
     * @var array Contains the field names which where modified
     * @access private
	 */
	var $modifiedValues;
	
	/**
	 * Storage of the last error
     * @var string Contains the error string of the last error occured
     * @access private
	 */
	var $lasterror;
	
	/**
	 * Checks for the virginity of this object. If true, the object
	 * is virgin and no operations on it except load-Functions are allowed.
     * @var boolean Contains the virginity of this object.
     * @access private
	 */
	var $virgin;
	
	/**
     * Cache the result items
     * @var array Contains all cache items
     * @access private
     */
    var $cache;
    
	/**
     * @var int Lifetime in seconds
     * @access private
     */
    var $lifetime;						
	
    /**
     * Constructor Function
     * @param string $table The table to use as information source
     * @param string $primaryKey The primary key to use
     */
    function Item($table = "", $primaryKey = "", $lifetime = 10)
    {
		$this->db = new DB_Contenido;
		
		if ($table == "")
		{
			$classname = get_parent_class($this);
			die("$classname: No table specified. Inherited classes *need* to set a table");
		} 

		if ($primaryKey == "")
		{
			die("No primary key specified. Inherited classes *need* to set a primary key");
		} 
		
		$this->table = $table;
		$this->primaryKey = $primaryKey;
		$this->virgin = true;
		$this->lifetime = $lifetime;
		
    } // end function

    /**
     * loadBy ($field, $value)
     * Loads an item by ID from the database
	 * @param string $field Specifies the field
	 * @param string $value Specifies the value
	 * @return bool True if the load was successful
     */
	function loadBy ($field, $value)
	{
		if (($this->cache[$sql]["time"] + $this->lifetime) >= time())
	    {
	    	$this->values = $this->cache[$sql]["values"];
	    } else {
	    		
    		/* SQL-Statement to select by field */
    		$sql = "SELECT * FROM ".
    				$this->table
    				." WHERE ".$field." = '".$value."'";
    		
    		/* Query the database */
    		$this->db->query($sql);
    		
    		/* Advance to the next record, return false if nothing found */
    		if (!$this->db->next_record())
    		{
    			return false;
    		}
    		
    		$this->values = $this->db->copyResultToArray();
    		
    		$this->cache[$sql]["time"] = time();
    		$this->cache[$sql]["values"] = $this->values;
	    }
		
		$this->virgin = false;
		return true;
	}

    /**
     * loadByPrimaryKey ($value)
     * Loads an item by ID from the database
	 * @param string $value Specifies the primary key value
	 * @return bool True if the load was successful
     */
    function loadByPrimaryKey ($value)
    {
    	return ($this->loadBy($this->primaryKey, $value));
    }
    
	/**
     * getField($field)
     * Gets the value of a specific field
	 * @param string $field Specifies the field to retrieve
	 * @return mixed Value of the field
     */
	function getField ($field)
	{
		if ($this->virgin == true)
		{
			$this->lasterror = i18n("No item loaded");
			return false;
		}

		return ($this->values[$field]);
	}

	/**
     * get($field)
     * Wrapper for getField (less to type)
	 * @param string $field Specifies the field to retrieve
	 * @return mixed Value of the field
     */
	function get ($field)
	{
		return $this->getField($field);
	}
				
	/**
     * setField($field, $value)
     * Sets the value of a specific field
	 * @param string $field Specifies the field to set
	 * @param string $value Specifies the value to set
	 * @param boolean $safe Speficies if we should translate characters
     */
	function setField ($field, $value, $safe = true)
	{
		if ($this->virgin == true)
		{
			$this->lasterror = i18n("No item loaded");
			return false;
		}

		$this->modifiedValues[$field] = true;
		
		if ($safe == true)
		{
			$this->values[$field] = htmlspecialchars($value);
		} else {
			$this->values[$field] = $value;
		}
	}
	
	/**
     * set($field, $value)
     * Shortcut to setField
	 * @param string $field Specifies the field to set
	 * @param string $value Specifies the value to set
     */
	function set ($field, $value, $safe = true)
	{
		return ($this->setField($field, $value, $safe));
	}	
	
	/**
     * store()
     * Stores the modified user object to the database
     */
	function store ()
	{
		if ($this->virgin == true)
		{
			$this->lasterror = i18n("No item loaded");
			return false;
		}
		
		$sql = "UPDATE " .$this->table ." SET ";
		$first = true;
		
		if (!is_array($this->modifiedValues))
		{
			return true;
		}
		
		foreach ($this->modifiedValues as $key => $value)
		{
			if ($first == true)
			{
				$sql .= "$key = '" . $this->values[$key] ."'";
				$first = false;
			} else {
				$sql .= ", $key = '" . $this->values[$key] ."'";
			}
		}
		
		$sql .= " WHERE ".$this->primaryKey." = '" . $this->values[$this->primaryKey]."'";
		
		$this->db->query($sql);
		
		if ($this->db->affected_rows() < 1)
		{
			return false;
		} else {
			return true;
		}
	}
	
} // end class

?>
