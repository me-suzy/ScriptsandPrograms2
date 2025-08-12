<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObjectSaveAction', 'pnt/web/actions');

/** Action that saves an object to the database. 
* Used by form from ObjectEditDetailsPage 
* when Insert or Update button is pressed. 
* Calls save method on the object.
* @see http://www.phppeanuts.org/site/index_php/Pagina/158
*
* This concrete subclass is here to keep de application developers
* code separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in the superclass
* This class may be copied to an application folder to
* make application specific overrides.
*/
class ObjectSaveAction extends PntObjectSaveAction {

	
}
?>