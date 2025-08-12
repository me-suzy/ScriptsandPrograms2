<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPagePart', 'pnt/web/parts');

/** Part that outputs html descirbing a menu.
* By default includes skinMenuPart.php from the includes folder.
* Includes skinSubMenu.php when printSubMenu is called with the
* application folder name as the argument.
*
* This abstract superclass provides behavior for the concrete
* subclass MenuPart in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
* @package pnt/web/parts
*/
class PntMenuPart extends PntPagePart {

	function PntMenuPart(&$whole, &$requestData)
	{
		$this->PntPage($whole, $requestData);
	}

	function getName() {
		return 'MenuPart';
	}

	function printSubmenu($name) {	
		
		if ($this->getDir() == 
				(($name && substr($name, -1) != '/') ? $name.'/' : $name)
			)
			$this->includeSkin('Submenu');
	}

}
?>