<?php
/*********************************************************************
CLASSE StatoLoghi: riferita alla tabella StatoLoghi di Mysql
**********************************************************************/
require_once("default.class.inc.php");

class Message extends defaultTable
{

	function Message () // costruttore
	{
		$this->tablename       = 'Message';

		$this->fieldspec['id'] = array('type' => 'int',
		'size' => '11',
		'pkey' => 'true',
		'required' => 'true',
		'auto_increment' => 'true');

		$this->fieldspec['idauthor'] = array('type' => 'varchar',
		'size' => '20',
		'required' => 'true');

		$this->fieldspec['comments'] = array('type' => 'text',
		'required' => 'true');

		$this->relationship_out[]   = array('one' => 'Person',
		'type' => 'restricted',
		'fields' => array('idauthor' => 'UserName'));


		$this->unique_keys[] = array();
	}

}