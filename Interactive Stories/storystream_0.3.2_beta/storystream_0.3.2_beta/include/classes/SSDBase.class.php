<?php
/** @file SSDBase.class.php
 *	Copyright (C) 2004  Karim Shehadeh
 *	
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *	
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *	
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *	@version 0.1
 *	@date October, 2003	
 */

/** @brief Base class for all objects in the system
	The base object will handle a variety of necessities
	required by most objects in the system.
*/
class SSDBase extends SSObject
{
	var $_connectionString = '';
	var $_db = NULL;
	var $_dbHandler = NULL;
	
	/** Constructor initiates connection with database */
	function SSDBase () {

		$this->_dbHandler = new DB;
		$this->generateConnectionString ();
	}
	
	/** Regenerates the connection string for the database and 
		stores it internally. 
		@return string Returns the generates connection string
	*/
	function generateConnectionString () {
		$dbType = $GLOBALS['CONFIG']->get ('db_type');
		$dbName = $GLOBALS['CONFIG']->get ('db_name');
		$dbHost = $GLOBALS['CONFIG']->get ('db_host');
		$dbUser = $GLOBALS['CONFIG']->get ('db_user');
		$dbPass = $GLOBALS['CONFIG']->get ('db_pass');
		$this->_connectionString = "$dbType://$dbUser:$dbPass@$dbHost/$dbName";
		
		return $this->_connectionString;
	}
	
	/** Initiates a connection to the database and returns the 
		database object.  This function determines the correct
		type of database object to return and will also check
		the configuration to determine what the host name, user
		name, password and database are to be used.
		
		@return DB_common A DB_common derived object is returned or false if there was an error. 
	*/
	function &connect () {
			
		$this->clearErrors ();
		
		// Make sure we have a valid connection string.
		if ($this->_connectionString == '')
			$this->generateConnectionString ();
			
		// Attempt to connect to the database
		$retValue = $this->_dbHandler->connect ($this->_connectionString, array('debug'=>3));		
		if (!DB::isError ($retValue)){
			$this->_db = &$retValue;
			$this->_db->setFetchMode (DB_FETCHMODE_ASSOC);
			
			return $this->_db;
		}
		else {	
            echo $retValue->getMessage();		
			$this->addErrorObject ($retValue,ERROR_TYPE_FATAL);
			return false;
		}
	}
	
};
?>
