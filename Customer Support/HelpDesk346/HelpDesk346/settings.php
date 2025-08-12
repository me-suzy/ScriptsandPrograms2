<?php
	class Settings
	{
		//define class attributes - non described private
		var $navigation;
		var $helpdesk;
		var $hdemail;
		var $result_page;
		var $hdticket;
		var $email_type;
		var $req_image;
		var $hdemail_up;
		var $hdemail_create;
		var $hdemail_close;
		var $ticketAccessModify;
		var $show_kb;
		var $allow_enduser_reg;
		var $max_file_size;
		var $enable_file_blocking;
		var $user_defined_priorities;
		var $ticket_lookup;
		var $hd_from;		//from address of the Helpdesk
		
		var $fname_block_list = array();
		var $fext_block_list = array();
		
		var $regex_fname = array();
		
		//Constant Arrays
		var $positions = array(
			"Is Exactly",
			"Starts With",
			"Contains",
			"Ends With"
		);
		var $results;
		
		function Settings()
		{
            print "<pre>";
			$q = "select * from " . DB_PREFIX . "settings LIMIT 1";
            var_dump($q);
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
            print_r($r);
            print "</pre>";
			
			$this->navigation				= $r['navigation'];
			$this->result_page 				= $r['result_page'];
			$this->hdticket					= $r['hdticket'];
			$this->email_type				= $r['email_type'];
			$this->req_image				= $r['req_image'];
			$this->hdemail_up				= $r['hdemail_up'];
			$this->hdemail_create			= $r['hdemail_create'];
			$this->hdemail_close			= $r['hdemail_close'];
			$this->ticketAccessModify		= $r['ticketAccessModify'];
			$this->show_kb					= $r['show_kb'];
			$this->allow_enduser_reg		= $r['allow_enduser_reg'];
			$this->max_file_size			= $r['max_file_size'];
			$this->enable_file_blocking 	= $r['enable_file_blocking'];
			$this->helpdesk					= $r['helpdesk'];
			$this->hdemail					= $r['hdemail'];
			$this->user_defined_priorities	= $r['user_defined_priorities'];
			$this->ticket_lookup			= $r['ticket_lookup'];
			$this->hd_from					= $r['HD_from'];
			
			$this->results					= mysql_num_rows($s) ? 1 : 0;
			
			$q = "select * from " . DB_PREFIX . "blocked_fnames";
			$s = mysql_query($q) or die(mysql_error());
			while ($r = mysql_fetch_assoc($s)) {
				$arr['id'] = $r['id'];
				$arr['value'] = $r['stringValue'];
				$arr['position'] = $this->positions[$r['position']];
				$this->fname_block_list[] = $arr;
			}
				
			$q = "select * from " . DB_PREFIX . "blocked_fexts";
			$s = mysql_query($q) or die(mysql_error());
			while ($r = mysql_fetch_assoc($s))
				$this->fext_block_list[] = $r['stringValue'];
		}
		
		function commit()
		{
			//this should never insert
			
			$cmd  = "update " . DB_PREFIX . "settings set ";
			$cmd .= "result_page = " . intval($this->result_page) . ", ";
			$cmd .= "hdticket = " . intval($this->hdticket) . ", ";
			$cmd .= "email_type = " . intval($this->email_type) . ", ";
			$cmd .= "req_image = " . intval($this->req_image) . ", ";
			$cmd .= "hdemail_up = " . intval($this->hdemail_up) . ", ";
			$cmd .= "hdemail_create = " . intval($this->hdemail_create) . ", ";
			$cmd .= "hdemail_close = " . intval($this->hdemail_close) . ", ";
			$cmd .= "ticketAccessModify = " . intval($this->ticketAccessModify) . ", ";
			$cmd .= "show_kb = " . intval($this->show_kb) . ", ";
			$cmd .= "allow_enduser_reg = " . intval($this->allow_enduser_reg) .. ", ";
			$cmd .= "max_file_size = " . intval($this->max_file_size) . ", ";
			$cmd .= "enable_file_blocking = " . intval($this->enable_file_blocking) . ", ";
			$cmd .= "user_defined_priorities = " . intval($this->user_defined_priorities) .", ";
			$cmd .= "navigation = '" . mysql_real_escape_string($this->navigation) . "', ";
			$cmd .= "helpdesk = '" . mysql_real_escape_string($this->helpdesk) . "', ";
			$cmd .= "ticket_lookup = " . intval($this->ticket_lookup) . ", ";
			$cmd .= "HD_from = '" . mysql_real_escape_string($this->hd_from) .. "'";
			
			#die(var_dump($cmd));
			mysql_query($cmd) or die(mysql_error());
			
			//READ THE INTERNAL ARRAYS AND ADD IN NEW EXTENSIONS/NAMES TO THE DATABASE
			//EXT FIRST
			mysql_query("truncate " . DB_PREFIX . "blocked_fexts") or die(mysql_error());
			foreach ($this->fext_block_list as $ext)
			{
				$cmd = "insert into " . DB_PREFIX . "blocked_fexts(stringValue) values('" . mysql_real_escape_string($ext) . "')";
				mysql_query($cmd) or die(mysql_error());	
			}
			
			//NOW NAMES
			mysql_query("truncate " . DB_PREFIX . "blocked_fnames") or die(mysql_error());
			foreach ($this->fname_block_list as $arr)
			{
				$q = "select id from " . DB_PREFIX . "blocked_fnames where stringValue = '" . mysql_real_escape_string($arr['value']) . "' and position = " . intval($arr['position']);
				if (!mysql_num_rows(mysql_query($q))) {
					if (!is_numeric($arr['position'])) {
						$match_array = array(
							"Matches" => 0,
							"Starts With" => 1,
							"Contains" => 2,
							"Ends With" => 3
						);
						
						$arr['position'] = $match_array[$arr['position']];	
					}
					$cmd = "insert into " . DB_PREFIX . "blocked_fnames(stringValue, position) values('" . $arr['value'] . "', " . $arr['position'] . ")";
					mysql_query($cmd) or die(mysql_error());
				}
			}
			
			//update complete
		}
		
		function insert()
		{
			//THIS FUNCTION SHOULD ONLY BE CALLED BY THE INSTALLER
			$cmd  = "insert into " . DB_PREFIX . "settings(navigation, helpdesk, result_page, email_type, show_kb, ticket_lookup, user_defined_priorities, HD_from) ";
			$cmd .= "values('$this->navigation', '$this->helpdesk', $this->result_page, $this->email_type, $this->show_kb, $this->ticket_lookup, $this->user_defined_priorities, '$this->hd_from')";
			
			mysql_query($cmd) or die(mysql_error());
		}
		
		function get($name)
		{
			return $this->$name;	
		}
		
		function set($name, $value)
		{
			$this->$name = $value;
		}
		
		
		//File sie functions
		function getSize()
		{
			$retVal = $this->max_file_size;		//we are gretting this in bytles
			$suffix = array("B", "KB", "MB", "GB", "TB", "PB");
			$ptr = 0;
			
			while ($retVal >= 1024)
			{
				$retVal /= 1024;
				$ptr++;	
			}
			
			return intval($retVal) . $suffix[$ptr];
		}
		
		function setSize($value)
		{
			//first parse out the size and determine multiplier and base	
			if (intval($value) <= 0) return false;
			else if (preg_match('/\d*KB?/i', $value)) {
				$size = ((1024) * intval($value));	
			}
			else if (preg_match('/\d*MB?/i', $value)) {
				$size = (((1024) * 1024) * intval($value));
			}
			else if (preg_match('/\d*GB?/i', $value)) {
				$size = ((((1024) * 1024) * 1024) * intval($value));
			}
			else if (preg_match('/\d*TB?/i', $value)) {
				$size = ((((1024) * 1024) * 1024) * intval($value) * 1024);	
			}
			else if (preg_match('/\d+/', $value)) {
				$size = intval($value);
			}
			
			$othersize = DetermineSize(ini_get('upload_max_filesize'));
		
			if ($size > $this->max_file_size || $othersize < $size) return false;
			else {
				$this->max_file_size = $size;
				return true;	
			}
		}
		
		//File Blockers Add and Delete Function
		//database is taken care of within functions
		function addExt($string)
		{
			if (!in_array($string, $this->fext_block_list))
				$this->fext_block_list[] = str_replace('.', '', $string);
		}
		
		function addName($string, $pos)
		{
			$q = "select id from " . DB_PREFIX . "blocked_fnames where stringValue = '" . mysql_real_escape_string($string) . "' and position = " . intval($pos);
			if (!mysql_num_rows(mysql_query($q))) {
				$q = "select max(id) from " . DB_PREFIX . "blocked_fnames";
				$id = mysql_result(mysql_query($q), 0) + 1;
				$arr['id'] = $id;
				$arr['value'] = $string;
				$arr['position'] = $pos;
				$this->fname_block_list[] = $arr;
			}
		}
		
		function delExt($string)
		{
			foreach ($this->fext_block_list as $k => $ext)
			{
				if ($ext == $string) {
					unset($this->fext_block_list[$k]);
					break;
				}
			}
		}
		
		function delName($id)
		{
			foreach ($this->fname_block_list as $k => $arr)
			{
				if ($arr['id'] == $id) {
					unset($this->fname_block_list[$k]);
					break;	
				}
			}
		}
		
		//returns a boolean (false) if bad
		function CheckFile($string)
		{
			if (!$this->enable_file_blocking) return true;
			//check the extensions
			if (count($this->fext_block_list))	$regex = "\.(" . implode('|', $this->fext_block_list) . ")$";
			#die($regex);
			if (isset($regex))
			{
				if (preg_match("/$regex/", $string)) return false;
			
				foreach ($this->fname_block_list as $index)
				{
					$arr = explode('.', $string);
					$string = $arr[0];
					switch ($index['position'])
					{
						case 0:
							$regex = $index['value']; break;
						case 1:
							$regex = "^" . $index['value']; break;
						case 2:
							$regex = "^.*" . $index['value'] . ".*$";
						case 3:
							$regex = $index['value'] . "$"; break;
						default: break;
					}
					
					if (preg_match("/$regex/", $string)) return false;
				}
			}
			
			return true;
		}
		
		function checkSize($size)
		{
			if (empty($size) || is_null($size)) return false;
			if ($size > $this->max_file_size) return false;
			return true;
		}
	}
?>