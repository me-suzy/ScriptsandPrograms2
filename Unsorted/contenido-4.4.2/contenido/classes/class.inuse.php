<?php

/*****************************************
* File      :   $RCSfile: class.inuse.php,v $
* Project   :   Contenido
* Descr     :   In-Use classes
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   18.07.2003
* Modified  :   $Date: 2003/07/31 10:08:49 $
*
* © four for business AG, www.4fb.de
*
* $Id: class.inuse.php,v 1.1 2003/07/31 10:08:49 timo.hummel Exp $
******************************************/

require_once($cfg["path"]["contenido"] . $cfg["path"]["classes"]. "class.genericdb.php");

/**
 * Class InUse
 * Class for In-Use management
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 0.1
 * @copyright four for business 2003
 */
class InUseCollection extends ItemCollection {
	
	/**
     * Constructor Function
     * @param none
     */
	function InUseCollection()
	{
		global $cfg;
		parent::ItemCollection($cfg["tab"]["inuse"], "idinuse");
	}

	/**
     * Loads an item by its ID (primary key)
     * @param $itemID integer Specifies the item ID to load
     */	
	function loadItem ($itemID)
	{
		$item = new InUseItem();
		$item->loadByPrimaryKey($itemID);
		return ($item);
	}
	
	/**
     * Marks a specific object as "in use". Note that
	 * items are released when the session is destroyed.
	 *
	 * Currently, the following types are defined and approved
	 * as internal Contenido standard:
	 * article
	 * module
	 * layout
     * template
 	 *
     * @param $type string Specifies the type to mark.
	 * @param $objectid mixed Specifies the object ID
	 * @param $session string Specifies the session for which the "in use" mark is valid
	 * @param $user string Specifies the user which requested the in-use flag
     */
   	function markInUse ($type, $objectid, $session, $user)
	{
		$this->select("type = '$type' AND objectid = '$objectid'");
		
		if (!$this->next())
		{
			$newitem = parent::create();
			$newitem->set("type", $type);
			$newitem->set("objectid", $objectid);
			$newitem->set("session", $session);
			$newitem->set("userid", $user);
			$newitem->store();
		}
			
	}


	/**
     * Removes the "in use" mark from a specific object.
 	 *
     * @param $type string Specifies the type to de-mark.
	 * @param $objectid mixed Specifies the object ID
	 * @param $session string Specifies the session for which the "in use" mark is valid
     */	
	function removeMark ($type, $objectid, $session)
	{
				
		$this->select("type = '$type' AND objectid = '$objectid' AND session = '$session'");
		
		if ($obj = $this->next())
		{
			/* Extract the ID */
			$id = $obj->get("idinuse");
			
			/* Let's save memory */
			unset($obj);
			
			/* Remove entry */
			$this->delete($id);
		}
	}

	/**
     * Removes all marks for a specific type and session
 	 *
     * @param $type string Specifies the type to de-mark.
	 * @param $session string Specifies the session for which the "in use" mark is valid
     */	
	function removeTypeMarks ($type, $session)
	{
				
		$this->select("type = '$type' AND session = '$session'");
		
		while ($this->next())
		{
			/* Extract the ID */
			$id = $obj->get("idinuse");
			
			/* Let's save memory */
			unset($obj);
			
			/* Remove entry */
			$this->delete($id);
		}
	}

	/**
     * Removes all in-use marks for a specific session.
 	 *
	 * @param $session string Specifies the session for which the "in use" marks should be removed
     */	
	function removeSessionMarks ($session)
	{
				
		$this->select("session = '$session'");
		
		while ($obj = $this->next())
		{
			/* Extract the ID */
			$id = $obj->get("idinuse");
			
			/* Let's save memory */
			unset($obj);
			
			/* Remove entry */
			$this->delete($id);
		}
	}
		
	/**
     * Checks if a specific item is marked
 	 *
     * @param $type string Specifies the type to de-mark.
	 * @param $objectid mixed Specifies the object ID
	 * @return int Returns false if it's not in use or returns the object if it is.
     */	
	function checkMark ($type, $objectid)
	{
		$this->select("type = '$type' AND objectid = '$objectid'");
		
		if ($obj = $this->next())
		{
			return ($obj);
		} else {
			return false;
		}
	}	


}

/**
 * Class InUseItem
 * Class for a single in-use item
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 0.1
 * @copyright four for business 2003
 */
class InUseItem extends Item {
	
	/**
     * Constructor Function
     * @param string $table The table to use as information source
     */
	function InUseItem()
	{
		global $cfg;
		
		parent::Item($cfg["tab"]["inuse"], "idinuse");
	}
	
}

?>