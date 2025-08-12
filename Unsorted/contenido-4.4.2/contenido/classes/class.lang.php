<?php

/**********************************************************************************
* File      :   $RCSfile: class.lang.php,v $
* Project   :   Contenido
* Descr     :   Class for language management and information
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   20.05.2003
* Modified  :   $Date: 2003/09/01 12:41:11 $
*
* © four for business AG, www.4fb.de
*
* This file is part of the Contenido Content Management System. 
*
* $Id: class.lang.php,v 1.3 2003/09/01 12:41:11 timo.hummel Exp $
***********************************************************************************/

cInclude("classes", "class.genericdb.php");

/**
 * Class Language
 * Class for language collections
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 0.1
 * @copyright four for business 2003
 */
class Languages extends ItemCollection {

    /**
     * Constructor
     * @param none
     */
    function Languages()
    {
    	global $cfg;
    	
    	/* Call the parent constructor with the table name
           and primary key to use. */
    	parent::ItemCollection($cfg["tab"]["lang"],"idlang");
    } 

 	function loadItem ($itemID)
	{
		$item = new Language();
		$item->loadByPrimaryKey($itemID);
		return ($item);
	}
	
	function nextAccessible()
	{
		global $perm, $client, $cfg;
		
		$item = parent::next();
		
		$db = new DB_Contenido;
		
		$sql = "SELECT idclient FROM ".$cfg["tab"]["clients_lang"]." WHERE idlang = '$idlang'";
		$db->query($sql);
		
		if ($db->next_record())
		{
			if ($client != $db->f("idclient"))
			{
				$item = $this->nextAccessible();
			}	
		}
		
		if ($item)
		{
			if ($perm->have_perm_client("lang[".$item->get("idlang")."]") ||
                $perm->have_perm_client("admin[".$client."]") ||
                $perm->have_perm_client())
            {
            	/* Do nothing for now */
            } else {
            	$item = $this->nextAccessible();
            }
            
            return $item;
		} else {
			return false;
		}
	}
	
	
} // end class

/**
 * Class Language
 * Class for a single language item
 * @author Timo A. Hummel <Timo.Hummel@4fb.de>
 * @version 0.1
 * @copyright four for business 2003
 */
class Language extends Item {
	
	/**
     * Constructor Function
     * @param none
     */
	function Language()
	{
		global $cfg;
		
		parent::Item($cfg["tab"]["lang"], "idlang");
	}
	
}
?>
