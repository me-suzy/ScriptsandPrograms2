<?
include_once "sys/template/Template.inc";

class Transferable extends Template
{
	var $data = array ();
	
	function Transferable ($data = -1) 
	{
		$this->Template ();
		if ($data == -1)
			return;
		
		if (is_array ($data))
			$this->data = $data;
	}
	
	function store ($data)
	{
		if (!is_array ($data))
			return false;
		
		$this->data = array_merge ($this->data, $data);
		return true;
	}
	
	function remove ($varName)
	{
		$newData = array ();
		
		foreach ($this->data as $name => $value)
			if ($name != $varName)
				$newData [$name] = $value;
		
		$this->data = $newData;
	}
	function inHidden ()
	{
		$hidden = "";
		foreach ($this->data as $name => $value)
			$hidden .= "<input type=hidden name=\"$name\" value=\"$value\">\n";
		
		return $hidden;
	}
	
	function inURL ()
	{
		global $PHP_SELF;
		
		$hidden = "";
		foreach ($this->data as $name => $value)
		{
			if (strlen ($hidden) > 0)
				$hidden .= "&";
			$hidden .= "$name=$value";
		}
		
		return $PHP_SELF."?".$hidden;	
	}
}
?>