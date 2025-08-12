<?php
	class Status
	{
		var $id;
		var $name;
		var $position = 1;
		var $icon = '';
		var $color = '';
		
		function Status($id = false)
		{
			if ($id) {
				$this->id = $id;
				$this->fetch();	
			}	
		}
		
		function fetch()
		{
			$q = "select * from " . DB_PREFIX . "status where id = $this->id";
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
			
	 		$this->id		= $r['id'];
	 		$this->name		= $r['name'];
	 		$this->position = $r['position'];
	 		$this->icon		= $r['icon'];
	 		$this->color	= $r['color'];
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
			switch ($name)
			{
				case 'color':
					mysql_query("update " . DB_PREFIX . "status set color = '' where color = '$value'"); break;
				case 'icon':
					mysql_query("update " . DB_PREFIX . "status set icon = '' where icon = '$value'"); break;
				default: break;	
			}
			
			if (is_null($app))
				$this->$name = $value;
			else
				$this->$name = $app($value);
		}
		
		function commit()
		{
			if ($this->id) {
				$cmd  = "update " . DB_PREFIX . "status set ";
				$cmd .= "name = '$this->name', ";
				$cmd .= "position = $this->position, ";
				$cmd .= "icon = '$this->icon', ";
				$cmd .= "color = '$this->color' ";
				$cmd .= "where id = $this->id";
                
                mysql_query($cmd) or die(mysql_error());
			}
			else {
				mysql_query("update " . DB_PREFIX . "status set position = position + 1");
				$cmd  = "insert into " . DB_PREFIX . "status(name, position, icon, color) ";
				$cmd .= "values('$this->name', $this->position, '$this->icon', '$this->color')";
                
                mysql_query($cmd) or die(mysql_error());
                $this->id = mysql_insert_id();
			}
		}
		
		function delete()
		{
			$q = "select id from " . DB_PREFIX . "data where status = $this->id LIMIT 1";
			if (mysql_num_rows(mysql_query($q))) return false;
			
			$cmd = "delete from " . DB_PREFIX . "status where id = $this->id";
			mysql_query($cmd) or die(mysql_error());
		}
		
		function moveUp()
		{
			$q = "select position, id from " . DB_PREFIX . "status where position >= $this->position order by position LIMIT 2";
			$s = mysql_query($q) or die(mysql_error());
			
			if (mysql_num_rows($s) >= 2) {
				//note how the results will be returned
				//current pid on top, and our target on the secoind row (offset 1)
				$stat = new Status(mysql_result($s, 1, 'id'));
				$temp = $this->position;
				$this->position = $stat->get('position', 'intval');
				$stat->set('position', $temp, 'intval');
				$stat->commit();
				$this->commit();
			}
		}
		
		function moveDown()
		{
			$q = "select position, id from " . DB_PREFIX . "status where position <= $this->position order by position desc LIMIT 2";
			$s = mysql_query($q) or die(mysql_error());
			
			if (mysql_num_rows($s) >= 2) {
				//note how the results will be returned
				//current pid on top, and our target on the second row (offset 1)
				$stat = new Status(mysql_result($s, 1, 'id'));
				$temp = $this->position;
				$this->position = $stat->get('position', 'intval');
				$stat->set('position', $temp, 'intval');
				$stat->commit();
				$this->commit();
			}
		}
	}
?>