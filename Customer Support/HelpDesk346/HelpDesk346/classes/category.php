<?php
	class Category
	{
		var $id;
		var $name;
		var $priority;
		
		function Category($id = false)
		{
			if ($id) {
				$this->id = $id;
				$this->fetch();	
			}	
		}
		
		function fetch()
		{
			$q = "select * from " . DB_PREFIX . "categories where id = $this->id";
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
			
			$this->id		= $r['id'];
			$this->name		= $r['name'];
			$this->priority = new Priority($r['priority']);	
		}
		
		function get($name, $callback = null)
		{
			if (is_null($callback)) {
				return $this->$name;
			}
			else {
				return $callback($this->$name);	
			}
		}
		
		function set($name, $value, $app = null)
		{
			if (is_null($app)) {
				$this->$name = $value;	
			}
			else {
				$this->$name = $app($value);	
			}
		}
		
		function commit()
		{
			if ($this->id) {
				//update
				$cmd  = "update " . DB_PREFIX . "categories set ";
				$cmd .= "name = '$this->name', ";
				
				$priority = $this->priority;		//runtime object resolution is not supported
													//an intermediatry step must be used
				$cmd .= "priority = " . intval($priority->get('pid')) . " ";
				$cmd .= "where id = $this->id";
                
                mysql_query($cmd) or die(mysql_error());
			}
			else {
				//insert
				$q = "select id from " . DB_PREFIX . "categories where name = '$this->name'";
				if (mysql_num_rows(mysql_query($q))) return;
				
				$priority = $this->priority;
				$cmd = "insert into " . DB_PREFIX . "categories(name, priority) values('$this->name', " . $priority->get('pid', 'intval') . ")";
                
                mysql_query($cmd) or die(mysql_error());
                $this->id = mysql_insert_id();
			}
		}
	}
?>