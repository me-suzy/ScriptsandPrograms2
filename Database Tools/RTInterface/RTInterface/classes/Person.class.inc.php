<?php
/*********************************************************************
CLASS Person
**********************************************************************/
require_once("default.class.inc.php");

class Person extends defaultTable
{

	function Person () // costruttore
	{
		$this->tablename       = 'Person';

		$this->fieldspec['id'] = array('type' => 'int',
		'size' => '11',
		'pkey' => 'true',
		'required' => 'true',
		'auto_increment' => 'true');

		$this->fieldspec['FirstName'] = array('type' => 'varchar',
		'size' => '20');

		$this->fieldspec['LastName'] = array('type' => 'varchar',
		'size' => '20');

		$this->fieldspec['UserName'] = array('type' => 'varchar',
		'size' => '20');

		$this->fieldspec['Birth'] = array('type' => 'date');

		$this->fieldspec['Note'] = array('type' => 'text');

		$this->relationship[]   = array('many' => 'Message',
		'type' => 'restricted',
		'fields' => array('id' => 'idauthor'));

		$this->unique_keys[] = array();
	}

}