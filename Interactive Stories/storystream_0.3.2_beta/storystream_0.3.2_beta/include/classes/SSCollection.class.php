<?php
/** @file SSCollection.class.php
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

/**  Represents an object that has a presence in a database
	Derive from this object if your new object represents a record
	in the database.  This class provides very useful methods for 
	accessing the database such as adding,removing and marking records
	as deleted.  Certain method MUST be overridden, though
*/
class SSCollection extends SSObject
{
	/** Constructor: Calls _addsProperties function to initialize object properties
	*/
	function SSCollection ($class, $callback='') {
		
		// The class property indicates the class type that is stored in the list
		$this->_addProperty ('class', $class);
		
		// An array of collected objects
		$this->_addProperty ('list', array ());
		
		// The callback property is the name of the method of 'class' that will be 
		//	called when an action needs to be taken on an object of type class
		$this->_addProperty ('callback', $callback);
	}

	/** Empties the list of objects in the collection
	 */
	function clear () {
		$this->set ('list', array ());
	}
	
	/**  Loads a constrained collection of objects from the database  ordered as specified
     *	Use this instead of doing a SELECT.  The constraints parameter is an assoc array
     *	where the key is the field name and the value is the value of the field to
     *	constrain to.  Note that if the value is a string, it must be pre-quoted.  The 
     *	orderBy parameter is an assoc array as well where the key is the field name
     *	to order by and the value is eithe 'DESC' or 'ASC'
     *	@param array $constraints An array of constraints to use when loading the collection (see descip)
     *	@param array $orderBy An array of sorting specifications (see descip)
	 *	@return bool False if there was a database error, true otherwise.
	*/
	function load ($constraints, $orderBy=array(), $distinctField='') {
		
		
		$className = $this->get ('class');
		$obj = new $className;
		if ($obj) {
			
			$selectTarget = '*';
			if ($distinctField) {
				$selectTarget .= ', DISTINCT ('.$distinctField.')';
			}
			
			$query = 'SELECT '.$selectTarget.' FROM '.$GLOBALS[$obj->_getTableConstant()]['name'].' WHERE 1';
			foreach ($constraints as $key=>$value) {
				$query .= ' AND '.$key.'='.$value;
			}

			if (count ($orderBy) > 0) {
			
				$query .= ' ORDER BY';
				$count = 0;
				foreach ($orderBy as $field=>$order) {
					$count++;
					if ($count > 1) {
						$query .= ',';
					}
					else {
						$query .= ' ';
					}
					
					$query .= $field.' '.$order;
				}
			}
			$results = $GLOBALS['DBASE']->getAssoc ($query);
			if (!DB::isError ($results)) {

				foreach ($results as $key=>$row) {

					// Add the key field to the array of fields.
					$results[$key][$obj->getUniqueID (true)] = $key;

					// Now load the data into the object.
					$obj->_setDBKeyValueArray ($results[$key]);

					// Now add the object to the collection
					$this->addObject ($obj);
				}
				
				return true;
			}
			else {

				$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			}
		}
		
		return false;
	}	
	
	/**  Retrieves all the objects stored in the collection as an array of objects
     *	@return array An array of objects stored in the collection
	*/
	function getObjects () {
	
		return $this->get ('list');
	}
	
	
	/**  Adds an object to the collection
     *	@param $obj The object to add to the collection
     *	@return bool True if added, false otherwise.
	*/
	function addObject ($obj) {
	
		$this->clearErrors ();
		if ((strcasecmp ($this->get ('class'), get_class ($obj)) == 0) ||
			is_subclass_of ($this->get('class'))) {
				
			$objects = $this->getObjects ();
			array_push ($objects, $obj);	
			$this->set ('list', $objects);
			
			return true;
		}
		else {
			$this->addError (sprintf (STR_34, $this->get('class'),get_class ($obj)), ERROR_TYPE_SERIOUS);
		}
				
		return false;
	}
}

?>