<?php
	class Priority
	{
		var $pid;
		var $name;
		var $severity = 1;		//they always go in at the bottom
		
		function Priority($pid = false)
		{
			if ($pid) {
				$this->pid = intval($pid);
				$this->fetch();	
			}
		}
		
		function fetch()
		{
			$q = "select * from " . DB_PREFIX . "priorities where pid = $this->pid LIMIT 1";
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
			
			$this->pid = $r['pid'];
			$this->name = $r['priority'];
			$this->severity = $r['severity'];
		}
		
		function get($name, $callback = null)
		{
			if (is_null($callback))
				return $this->$name;
			else
				return $callback($this->$name);	
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
			if ($this->pid) {
				//update
				$cmd  = "update " . DB_PREFIX . "priorities set ";
				$cmd .= "priority = '$this->name', ";
				$cmd .= "severity = $this->severity ";
				$cmd .= "where pid = $this->pid";
                
                mysql_query($cmd) or die(mysql_error());
			}
			else {
				//insert
				mysql_query("update " . DB_PREFIX . "priorities set severity = severity + 1");
				$cmd = "insert into " . DB_PREFIX . "priorities(priority, severity) values('$this->name', $this->severity)";
                
                mysql_query($cmd) or die(mysql_error());
                $this->pid = mysql_insert_id();
			}
		}
		
		function delete()
		{
			$q = "select * from " . DB_PREFIX . "priorities LIMIT 1";
			$s = mysql_query($q) or die(mysql_error());
			if ( (mysql_num_rows($s) - 1) ) return;
			
			$cmd = "delete from " . DB_PREFIX . "priorities where pid = $this->pid";
			mysql_query($cmd) or die(mysql_error());
			
			//update data table
			$q = "select min(pid) from " . DB_PREFIX . "priorities";
			$s = mysql_query($q) or die(mysql_error());
			
			$cmd = "update " . DB_PREFIX . "data set priority = " . mysql_result($s, 0) . " where priority = $this->pid";
			mysql_query($cmd) or die(mysql_error());	
		}
		
		function IncreaseServerity()
		{	
			//get our new severity and id
			$q = "select severity, pid from " . DB_PREFIX . "priorities where severity >= $this->severity order by severity LIMIT 2";
			$s = mysql_query($q) or die(mysql_error());
			
			if (mysql_num_rows($s) >= 2) {
				//note how the results will be returned
				//current pid on top, and our target on the secoind row (offset 1)
				$p = new Priority(mysql_result($s, 1, 'pid'));
				$temp = $this->severity;
				$this->severity = $p->get('severity', 'intval');
				$p->set('severity', $temp, 'intval');
				$p->commit();
				$this->commit();
			}
			//if this check fails then there is only one priority in the databse
			//so changing severity would be pointless
		}
		
		function DecreaseSeverity()
		{
			//get our new severity and id
			$q = "select severity, pid from " . DB_PREFIX . "priorities where severity <= $this->severity order by severity desc LIMIT 2";
			$s = mysql_query($q) or die(mysql_error());
			
			if (mysql_num_rows($s) >= 2) {
				//note how the results will be returned
				//current pid on top, and our target on the secoind row (offset 1)
				$p = new Priority(mysql_result($s, 1, 'pid'));
				$temp = $this->severity;
				$this->severity = $p->get('severity', 'intval');
				$p->set('severity', $temp, 'intval');
				$p->commit();
				$this->commit();
			}
			//if this check fails then there is only one priority in the databse
			//so changing severity would be pointless
		}
	}
?>