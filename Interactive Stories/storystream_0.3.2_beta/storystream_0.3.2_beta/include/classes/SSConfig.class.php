<?php
/** @file SSConfig.class.php
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

/**  Handles configuration data used by the system
 *	The configuration data is a singleton that 
 *	initializes itself with data retrieves from a config
 *	file stored somewhere on the server. This is done
 *	once for each time a script is run by the web
 *	server.
*/
class SSConfig extends SSObject
{
	/** Constructor: Loads configuration data into a property list */
	function SSConfig () {
		
		// Database
		$this->_addProperty ('db_type', $GLOBALS['db_type']);
		$this->_addProperty ('db_user', $GLOBALS['db_user']);
		$this->_addProperty ('db_pass', $GLOBALS['db_pass']);
		$this->_addProperty ('db_host', $GLOBALS['db_host']);
		$this->_addProperty ('db_name', $GLOBALS['db_name']);
		$this->_addProperty ('db_table_prefix', $GLOBALS['db_table_prefix']);
				
		$this->_addProperty ('site_url', $GLOBALS['baseUrl']);

		if (($this->get ('db_type') == '') || ($this->get ('db_host') == '')||
			($this->get ('db_user') == '') || ($this->get ('db_name') == '')) {
			
			$this->addError (STR_35, ERROR_TYPE_FATAL);
		}

		$this->_addProperty ('admin_email_errors', $GLOBALS['admin_email_errors']);
		$this->_addProperty ('admin_email', $GLOBALS['admin_email']);
		$this->_addProperty ('language', $GLOBALS['language']);		
		//$this->_addProperty ('server_os', $GLOBALS['server_os']);
	}	
}
?>
