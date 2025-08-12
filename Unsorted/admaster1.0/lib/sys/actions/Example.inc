<?
include_once "sys/template/Action.inc";

class Example extends Action
{	
	// names of the actions  and the names of methods 
	// that will process them
	var $action        = array ('Example'      => 'exampleMethod');
								
	// default action actionVar
	var $defActionName = 'Example'; 	
	
	function Example ()
	{
		$this->Action ();
	}
	
	function exampleMethod ()
	{
		$this->add ("<font color=green>Example:Example method test</font>");
	}
}
?>