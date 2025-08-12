<?php

class SSVersionControl  extends SSObject {
	
	/** The object that is being versioned */
	var $_obj = NULL;
	
	/** 
	 * Sets the object that is to be scrutinized for version history information
	 * @param SSObject $obj An SSObj-derived object.
	 */
	function setObject ($obj) {
		$this->_obj = $obj;
	}
	
	/** 
	 * Retrieves the history of the active object as an array where each item
	 *	in the array is an associative array containing the file nodes:
	 *
	 *	'version': A number denoting the version of the object
	 *	'date': The date that the change was made.
	 *	'object': The object at that point.
	 *	'changes': An array of changes described using natural language.
	 *
	 * @return array See the description above for what is contained in the array.
	 */ 
	function getHistory () {
		
		// First, check and see if it's a scene,fork or story object
		$type = $this->_obj->getType ();
		if ($type == OBJECT_TYPE_STORY ||
			$type == OBJECT_TYPE_SCENE ||
			$type == OBJECT_TYPE_FORK) {
				
			// If it is, then get all changes that have been made to the object
			//	sorted by date in descending order.		
			$table = $GLOBALS['TABLE_MOD']['name'];
			$userField = $GLOBALS['TABLE_MOD']['fields']['USER_ID'];
			$dateField = $GLOBALS['TABLE_MOD']['fields']['MOD_DATE'];
			$actionField = $GLOBALS['TABLE_MOD']['fields']['ACTION'];
			$subjectTypeField = $GLOBALS['TABLE_MOD']['fields']['TARGET_TYPE'];
			$subjectIDField = $GLOBALS['TABLE_MOD']['fields']['TARGET_ID'];
			
			// We have to see if any scenes or forks were added or changed
			//	also.			
			$id = $this->_obj->getUniqueID();
			$query = "SELECT * FROM ".$table." WHERE ($subjectTypeField=$type 
				AND $subjectIDField=$id) ORDER BY ".$dateField." ASC";
			
			$results = $GLOBALS['DBASE']->simpleQuery ($query);
			if (!DB::isError ($results)) {
							
				$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
				$version = 1;
				$previousVersion = NULL;
				
				$history = array ();
				// Loop through each modification and put together a list of all
				//	the history data.				
				while (($array = $resultObj->fetchRow ())) {
					
					$date = $array[$GLOBALS['TABLE_MOD']['fields']['MOD_DATE']];

                    // In some cases, the data can be corrupted for some reason so
                    //  don't go nuts if the unserialize fails.
                    $original = error_reporting (0);
					$object = @unserialize ($array[$GLOBALS['TABLE_MOD']['fields']['MOD_DATA']]);
					error_reporting ($original);
					
					if ($object) {
						$info['date'] = $date;
						$info['object'] = $object;
						$info['version'] = $version++;
						$info['author'] = $array[$GLOBALS['TABLE_MOD']['fields']['USER_ID']];
						$info['ip'] = $array[$GLOBALS['TABLE_MOD']['fields']['MOD_IP']];
												
						$bc = new SSBrowserCap ($array[$GLOBALS['TABLE_MOD']['fields']['CLIENT_INFO']]);
						$info['browser'] = $bc->property ('long_name').' '.$bc->property('version').' ('.$bc->property('platform').' '.$bc->property('os').')';
						
						if ($previousVersion) {
							$info['changes'] = $this->_getChanges ($previousVersion, $object);
						}
						else {
							$info['changes'] = array ('This is the first version');
						}

						$previousVersion = $object;
						$history[] = $info;
					}
				}
				
				return $history;
			}
			else {
				$this->addError($results);
			}
			
		}
		
		return false;
	}
	
	/** 
	 * This will create an array of natural language descriptions of the
	 * changes made to the next version of the object since the subsequent 
	 * previous version.
	 * @param SSObject $prev The previous object to compare against.
	 * @param SSObject $next The newer object that changed.
	 * @return array An array of natural change descriptions.
	 */
	function _getChanges ($prev,$next) {
		
		$changes = array ();
		foreach ($prev->_properties as $key=>$value) {
			
			if (isset ($next->_properties[$key])) {				
				if ($value != $next->_properties[$key]) {				
					$propinfo = $next->getPropertyInfo ($key);
					
					if ($propinfo['diff']) {
						$v1 = $prev->get ($key);
						$v2 = $next->get ($key);
						if (is_string ($v1) && (strlen ($v1) < 100) && (strlen($v2) < 100)) {
							$text = 'The  '.$propinfo['name'].' field changed from "'.$prev->get ($key).'" to "'.$next->get($key).'"';
						}
						else {
							$text = 'The '.$propinfo['name'].' field changed.  The differences are too large to display here, though.';
						}
					}
					else {
						$text = 'The '.$propinfo['name'].' field was changed';
					}
					$changes[] = $text;
				}
			}
			else {
				$changes[] = 'The '.$key.' property was changed';
			}
		}
		return $changes;
	}
}
?>
