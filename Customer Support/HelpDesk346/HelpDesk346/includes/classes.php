<?php
	//include the helper classes
	include_once './classes/priority.php';
	include_once './classes/category.php';
	include_once './classes/status.php';
	
	//Include the Main Classes
	include_once './classes/ticket.php';
	include_once './classes/user.php';
	
	//Include the Sub Classes
	include_once "./classes/subclasses/HeldTicket.php";
	include_once "./classes/subclasses/PublishedTicket.php";
	
	//custom functions definitions
	function printValue($val)
	{
		if (empty($val))
			return 'None Given';
		else
			return $val;	
	}
?>