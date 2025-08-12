<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntMenuPart', 'pnt/web/parts');

/** Part that outputs html descirbing a menu.
* By default includes skinMenuPart.php from the includes folder.
* Includes skinSubMenu.php when printSubMenu is called with the
* application folder name as the argument.
*
* This concrete subclass is here to keep de application developers
* code separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass.
* This class may be copied to an application folder to
* make application specific overrides.
* @see http://www.phppeanuts.org/site/index_php/Pagina/65
*/
class MenuPart extends PntMenuPart {

}
?>