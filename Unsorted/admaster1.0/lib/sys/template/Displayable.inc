<?

class Displayable
{
	var $body = "";
	
	function Displayable () {}
	
	function add ($body)
	{
		$this->body = $body;
	}
	function replace ($alias, $value)
	{
		$this->body = str_replace ($alias, $value, $this->body);
	}
	function display ()
	{
		return $this->body;
	}
}
?>